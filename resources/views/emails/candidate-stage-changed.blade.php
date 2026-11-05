<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
  body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
  .container { max-width: 560px; margin: 40px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
  .header { background: linear-gradient(135deg, #38009A, #9E0059); padding: 32px 36px; }
  .header h1 { color: #fff; margin: 0; font-size: 20px; font-weight: 700; }
  .header p { color: rgba(255,255,255,0.7); margin: 6px 0 0; font-size: 14px; }
  .body { padding: 32px 36px; }
  .stage-badge { display: inline-block; background: #EBE6F5; color: #38009A; padding: 4px 14px; border-radius: 999px; font-size: 13px; font-weight: 600; margin: 0 0 20px; }
  .message { color: #374151; font-size: 15px; line-height: 1.6; }
  .custom-body { background: #F9FAFB; border-left: 3px solid #38009A; padding: 14px 18px; border-radius: 0 8px 8px 0; color: #374151; font-size: 14px; line-height: 1.6; margin: 20px 0; }
  .footer { padding: 20px 36px; border-top: 1px solid #E5E7EB; color: #9CA3AF; font-size: 12px; }
</style>
</head>
<body>
<div class="container">
  <div class="header">
    <h1>Application Update</h1>
    <p>{{ $application->job?->organization?->name ?? 'Jobify' }}</p>
  </div>
  <div class="body">
    <div class="stage-badge">{{ $stageName }}</div>
    <div class="message">
      <p>Hi {{ $application->candidate?->user?->first_name ?? 'there' }},</p>
      <p>Your application for <strong>{{ $application->job?->title }}</strong> has been updated. You have progressed to the <strong>{{ $stageName }}</strong> stage.</p>
      @if(!empty($automation->action_config['body']))
      <div class="custom-body">{{ $automation->action_config['body'] }}</div>
      @else
      @if($automation->action_type === 'schedule_interview')
      <p>Our team will be in touch shortly with interview details. Please keep an eye on your inbox.</p>
      @elseif($automation->action_type === 'request_assessment')
      <p>As part of the next step, we'd like you to complete an assessment. Our team will send you the details shortly.</p>
      @else
      <p>We appreciate your patience and will keep you informed of any further updates.</p>
      @endif
      @endif
    </div>
  </div>
  <div class="footer">This email was sent automatically by the Jobify hiring platform. Please do not reply to this email.</div>
</div>
</body>
</html>
