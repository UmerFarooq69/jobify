<?php
namespace App\Http\Controllers\Jobify;

use App\Http\Controllers\Controller;
use App\Models\CandidateProfile;
use App\Models\OrganizationUser;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'identifier' => ['required', 'string', 'max:255'],
            'role'       => ['required', 'string', 'in:candidate,company_admin'],
        ]);

        $identifier = $request->identifier;

        Otp::where('identifier', $identifier)->whereNull('used_at')->delete();

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $type = str_contains($identifier, '@') ? 'email' : 'phone';

        Otp::create([
            'identifier' => $identifier,
            'type'       => $type,
            'otp'        => $code,
            'role'       => $request->role,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Always log OTP so it's findable in storage/logs/laravel.log
        Log::info(">>> OTP for {$identifier} ({$request->role}): {$code}");

        if ($type === 'email') {
            $apiKey = env('RESEND_API_KEY');
            if ($apiKey) {
                try {
                    $res = Http::withToken($apiKey)
                        ->post('https://api.resend.com/emails', [
                            'from'    => config('mail.from.address', 'onboarding@resend.dev'),
                            'to'      => [$identifier],
                            'subject' => 'Your Jobify OTP Code',
                            'text'    => "Your Jobify verification code is: {$code}\n\nThis code expires in 10 minutes.",
                        ]);
                    if (! $res->successful()) {
                        Log::warning("Resend API error for {$identifier}: " . $res->body());
                    }
                } catch (\Throwable $e) {
                    Log::warning("Resend HTTP failed for {$identifier}: " . $e->getMessage());
                }
            } else {
                try {
                    Mail::raw(
                        "Your Jobify verification code is: {$code}\n\nThis code expires in 10 minutes.",
                        fn ($m) => $m->to($identifier)->subject('Your Jobify OTP Code')
                    );
                } catch (\Throwable $e) {
                    Log::warning("OTP email failed for {$identifier}: " . $e->getMessage());
                }
            }
        }

        $response = ['success' => true];
        if (app()->environment('local')) {
            $response['dev_otp'] = $code;
        }

        return response()->json($response);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'identifier' => ['required', 'string'],
            'otp'        => ['required', 'string', 'size:6'],
            'role'       => ['nullable', 'string', 'in:candidate,company_admin'],
        ]);

        $otp = Otp::where('identifier', $request->identifier)
            ->where('otp', $request->otp)
            ->whereNull('used_at')
            ->latest()
            ->first();

        if (! $otp || ! $otp->isValid()) {
            return response()->json([
                'errors' => ['otp' => ['Invalid or expired code. Please try again.']],
            ], 422);
        }

        $otp->update(['used_at' => now()]);

        $role    = $otp->role ?? $request->role ?? 'candidate';
        $isEmail = str_contains($request->identifier, '@');

        $user = $isEmail
            ? User::firstOrCreate(
                ['email' => $request->identifier],
                [
                    'name'             => 'User',
                    'role_type'        => $role,
                    'status'           => 'active',
                    'email_verified_at' => now(),
                    'password'         => bcrypt(\Illuminate\Support\Str::random(32)),
                ]
            )
            : User::firstOrCreate(
                ['phone' => $request->identifier],
                [
                    'name'              => 'User',
                    'role_type'         => $role,
                    'status'            => 'active',
                    'phone_verified_at' => now(),
                    'password'          => bcrypt(\Illuminate\Support\Str::random(32)),
                ]
            );

        if (! $user->role_type) {
            $user->update(['role_type' => $role]);
        }

        Auth::login($user, true);
        $request->session()->regenerate(false); // preserve CSRF token

        $csrf = csrf_token();

        if ($user->role_type === 'candidate') {
            $completed = CandidateProfile::where('user_id', $user->id)
                ->where('onboarding_completed', true)
                ->exists();
            if (! $completed) {
                return response()->json(['redirect' => '/candidate/onboarding', 'csrf' => $csrf]);
            }
            return response()->json(['redirect' => '/candidate/dashboard', 'csrf' => $csrf]);
        }

        if ($user->role_type === 'company_admin') {
            $hasCompany = OrganizationUser::where('user_id', $user->id)->exists();
            if (! $hasCompany) {
                return response()->json(['redirect' => '/employer/setup', 'csrf' => $csrf]);
            }
            return response()->json(['redirect' => '/employer/dashboard', 'csrf' => $csrf]);
        }

        return response()->json(['redirect' => '/', 'csrf' => $csrf]);
    }

    public function employerLoginPage()
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->role_type === 'company_admin') return redirect('/employer/dashboard');
            return redirect('/');
        }
        return Inertia::render('Jobify/Company/Login');
    }

    public function employerLogin(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->boolean('remember'))) {
            return response()->json([
                'errors' => ['email' => ['These credentials do not match our records.']],
            ], 422);
        }

        $user = Auth::user();
        $request->session()->regenerate();
        $csrf = csrf_token();

        if ($user->role_type === 'platform_admin' || $user->role === 'admin') {
            $redirect = '/platform-admin/dashboard';
        } elseif ($user->role_type === 'company_admin') {
            $hasCompany = OrganizationUser::where('user_id', $user->id)->exists();
            $redirect   = $hasCompany ? '/employer/dashboard' : '/employer/setup';
        } else {
            $redirect = '/candidate/dashboard';
        }

        return response()->json(['redirect' => $redirect, 'csrf' => $csrf]);
    }
}
