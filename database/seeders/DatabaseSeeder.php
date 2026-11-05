<?php

namespace Database\Seeders;

use App\Models\CandidateProfile;
use App\Models\Department;
use App\Models\JobRequisition;
use App\Models\JobTaxonomy;
use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Models\PipelineStage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Platform Admin ──────────────────────────────────────────────────
        $admin = User::updateOrCreate(
            ['email' => 'admin@jobify.com'],
            [
                'name'              => 'Platform Admin',
                'first_name'        => 'Platform',
                'last_name'         => 'Admin',
                'password'          => bcrypt('password123'),
                'role'              => 'admin',
                'role_type'         => 'platform_admin',
                'status'            => 'active',
                'active'            => true,
                'auth_provider'     => 'local',
                'email_verified_at' => now(),
            ]
        );
        $this->command->info('Platform admin: admin@jobify.com / password123  →  /platform-admin');

        // ── 2. Job Taxonomies ──────────────────────────────────────────────────
        $jobTypes = [
            ['type' => 'job_type', 'name' => 'Full Time',   'sort_order' => 1],
            ['type' => 'job_type', 'name' => 'Part Time',   'sort_order' => 2],
            ['type' => 'job_type', 'name' => 'Contract',    'sort_order' => 3],
            ['type' => 'job_type', 'name' => 'Internship',  'sort_order' => 4],
            ['type' => 'job_type', 'name' => 'Freelance',   'sort_order' => 5],
        ];
        $jobFunctions = [
            ['type' => 'job_functionality', 'name' => 'Information Technology', 'sort_order' => 1],
            ['type' => 'job_functionality', 'name' => 'Engineering',             'sort_order' => 2],
            ['type' => 'job_functionality', 'name' => 'Finance & Accounting',    'sort_order' => 3],
            ['type' => 'job_functionality', 'name' => 'Sales & Marketing',       'sort_order' => 4],
            ['type' => 'job_functionality', 'name' => 'Human Resources',         'sort_order' => 5],
            ['type' => 'job_functionality', 'name' => 'Healthcare',              'sort_order' => 6],
            ['type' => 'job_functionality', 'name' => 'Education',               'sort_order' => 7],
            ['type' => 'job_functionality', 'name' => 'Operations',              'sort_order' => 8],
        ];
        foreach ([...$jobTypes, ...$jobFunctions] as $tax) {
            JobTaxonomy::firstOrCreate(['type' => $tax['type'], 'name' => $tax['name']], array_merge($tax, ['active' => true]));
        }
        $fullTime = JobTaxonomy::where('type', 'job_type')->where('name', 'Full Time')->first();
        $itFunc   = JobTaxonomy::where('type', 'job_functionality')->where('name', 'Information Technology')->first();
        $this->command->info('Job taxonomies seeded.');

        // ── 3. Demo Employer ───────────────────────────────────────────────────
        $employer = User::updateOrCreate(
            ['email' => 'employer@jobify.com'],
            [
                'name'              => 'Demo Employer',
                'first_name'        => 'Demo',
                'last_name'         => 'Employer',
                'password'          => bcrypt('password123'),
                'role_type'         => 'company_admin',
                'status'            => 'active',
                'active'            => true,
                'auth_provider'     => 'local',
                'email_verified_at' => now(),
            ]
        );

        $org = Organization::firstOrCreate(
            ['slug' => 'ecare-demo'],
            [
                'name'              => 'eCare Technology',
                'industry'          => 'Technology',
                'city'              => 'Doha',
                'country'           => 'QA',
                'website'           => 'https://ecare.qa',
                'size'              => '51-200',
                'description'       => 'eCare is a dynamic technology company delivering innovative software solutions to clients across industries. We are headquartered in Doha, Qatar.',
                'moderation_status' => 'approved',
            ]
        );

        OrganizationUser::firstOrCreate(
            ['user_id' => $employer->id, 'organization_id' => $org->id],
            ['role' => 'admin']
        );
        $employer->update(['role_type' => 'company_admin']);

        // Departments
        $departments = ['Engineering', 'Product', 'Design', 'Operations', 'Sales'];
        $deptIds = [];
        foreach ($departments as $deptName) {
            $dept = Department::firstOrCreate(['organization_id' => $org->id, 'name' => $deptName]);
            $deptIds[$deptName] = $dept->id;
        }

        $this->command->info('Employer: employer@jobify.com / password123  →  /employer/dashboard');

        // ── 4. Sample Jobs ─────────────────────────────────────────────────────
        $jobs = [
            [
                'title'           => 'Software Engineer - Full Stack',
                'summary'         => 'eCare is seeking a motivated Software Engineer to design and implement scalable software solutions. You will work across the full development lifecycle with cross-functional teams.',
                'description'     => '<h2>About eCare</h2><p>eCare is a dynamic technology company delivering innovative software solutions to clients across industries. We are headquartered in Doha, Qatar, with a culture that emphasizes collaboration, continuous learning, and growth.</p><h2>Role Overview</h2><p>We are seeking a Software Engineer to design, develop, and maintain scalable web applications across the full stack.</p><h2>Responsibilities</h2><ul><li>Design, develop, and maintain front end and back end components</li><li>Collaborate with cross-functional teams to refine requirements</li><li>Write clean, well tested code and participate in code reviews</li><li>Ensure performance, scalability, and security best practices</li></ul>',
                'requirements'    => '<h2>Requirements</h2><ul><li>Bachelor\'s degree in Computer Science or related field</li><li>3+ years of software development experience</li><li>Strong proficiency in JavaScript and TypeScript</li><li>Experience with React or similar front end framework</li><li>Experience building RESTful APIs and databases (SQL and NoSQL)</li><li>Familiarity with CI/CD, version control with Git</li></ul><h2>Benefits</h2><ul><li>Competitive salary and performance-based bonus</li><li>Health insurance and paid time off</li><li>Professional development stipend</li><li>Flexible work arrangements</li></ul>',
                'skills'          => ['PHP', 'Laravel', 'React', 'TypeScript', 'MySQL', 'REST API'],
                'salary_min'      => 9000,
                'salary_max'      => 17000,
                'location'        => 'Doha, Qatar',
                'employment_type' => 'full_time',
                'department'      => 'Engineering',
            ],
            [
                'title'           => 'UI/UX Designer',
                'summary'         => 'Join our design team to create beautiful, user-centric digital experiences for our clients across the MENA region.',
                'description'     => '<h2>About the Role</h2><p>We are looking for a talented UI/UX Designer to join our growing design team. You will be responsible for designing intuitive and visually appealing interfaces.</p><h2>Key Responsibilities</h2><ul><li>Create wireframes, prototypes, and high-fidelity designs</li><li>Conduct user research and usability testing</li><li>Collaborate with developers to ensure design integrity</li><li>Maintain and evolve our design system</li></ul>',
                'requirements'    => '<ul><li>3+ years of UI/UX design experience</li><li>Proficiency in Figma or Adobe XD</li><li>Strong portfolio demonstrating design skills</li><li>Understanding of responsive design principles</li><li>Experience with user research methodologies</li></ul>',
                'skills'          => ['Figma', 'Adobe XD', 'User Research', 'Prototyping', 'Design Systems'],
                'salary_min'      => 7000,
                'salary_max'      => 13000,
                'location'        => 'Doha, Qatar',
                'employment_type' => 'full_time',
                'department'      => 'Design',
            ],
            [
                'title'           => 'DevOps Engineer',
                'summary'         => 'Help us scale our infrastructure and automate deployments across cloud environments.',
                'description'     => '<h2>About the Role</h2><p>We are seeking an experienced DevOps Engineer to manage and improve our cloud infrastructure, CI/CD pipelines, and monitoring systems.</p><h2>Responsibilities</h2><ul><li>Design and maintain CI/CD pipelines</li><li>Manage cloud infrastructure on AWS/Azure</li><li>Implement monitoring and alerting solutions</li><li>Collaborate with development teams on deployment strategies</li></ul>',
                'requirements'    => '<ul><li>4+ years of DevOps/infrastructure experience</li><li>Strong knowledge of Docker, Kubernetes</li><li>Experience with AWS or Azure</li><li>Proficiency in scripting (Bash, Python)</li><li>Experience with Terraform or similar IaC tools</li></ul>',
                'skills'          => ['Docker', 'Kubernetes', 'AWS', 'CI/CD', 'Terraform', 'Linux'],
                'salary_min'      => 11000,
                'salary_max'      => 20000,
                'location'        => 'Doha, Qatar',
                'employment_type' => 'full_time',
                'department'      => 'Engineering',
            ],
            [
                'title'           => 'Product Manager',
                'summary'         => 'Drive product strategy and roadmap for our core SaaS platform serving enterprise clients in Qatar.',
                'description'     => '<h2>About the Role</h2><p>We are looking for an experienced Product Manager to lead the development of our flagship SaaS product. You will work at the intersection of business, technology, and design.</p><h2>Responsibilities</h2><ul><li>Define and communicate product vision and strategy</li><li>Gather and prioritize product requirements</li><li>Work closely with engineering, design, and sales teams</li><li>Analyze market trends and competitive landscape</li></ul>',
                'requirements'    => '<ul><li>5+ years of product management experience</li><li>Experience with B2B SaaS products</li><li>Strong analytical and problem-solving skills</li><li>Excellent communication and stakeholder management</li><li>Experience with agile methodologies</li></ul>',
                'skills'          => ['Product Strategy', 'Agile', 'JIRA', 'User Stories', 'Roadmapping', 'Analytics'],
                'salary_min'      => 15000,
                'salary_max'      => 25000,
                'location'        => 'Doha, Qatar',
                'employment_type' => 'full_time',
                'department'      => 'Product',
            ],
            [
                'title'           => 'Sales Executive',
                'summary'         => 'Drive revenue growth by identifying and closing new business opportunities with enterprise clients across Qatar.',
                'description'     => '<h2>About the Role</h2><p>We are seeking a dynamic Sales Executive to join our commercial team. You will be responsible for identifying, developing, and closing new business opportunities.</p><h2>Responsibilities</h2><ul><li>Develop and execute sales strategies to meet targets</li><li>Build and maintain relationships with key enterprise clients</li><li>Conduct product demonstrations and presentations</li><li>Collaborate with marketing to generate qualified leads</li></ul>',
                'requirements'    => '<ul><li>3+ years of B2B sales experience</li><li>Proven track record of meeting sales quotas</li><li>Excellent negotiation and presentation skills</li><li>Experience selling SaaS or technology solutions</li><li>Fluent in English; Arabic is a plus</li></ul>',
                'skills'          => ['B2B Sales', 'CRM', 'Negotiation', 'SaaS', 'Pipeline Management'],
                'salary_min'      => 8000,
                'salary_max'      => 15000,
                'location'        => 'Doha, Qatar',
                'employment_type' => 'full_time',
                'department'      => 'Sales',
            ],
        ];

        foreach ($jobs as $jobData) {
            $deptName = $jobData['department'];
            unset($jobData['department']);

            $existing = JobRequisition::where('organization_id', $org->id)
                ->where('title', $jobData['title'])
                ->first();

            if ($existing) continue;

            $job = $org->jobRequisitions()->create(array_merge($jobData, [
                'uuid'              => Str::uuid(),
                'status'            => 'active',
                'moderation_status' => 'approved',
                'department_id'     => $deptIds[$deptName] ?? null,
                'job_type_id'       => $fullTime?->id,
                'job_function_id'   => $itFunc?->id,
                'currency'          => 'QAR',
            ]));

            foreach (['Application Review', 'Phone Screen', 'Interview', 'Offer'] as $i => $stage) {
                $job->pipelineStages()->firstOrCreate(['name' => $stage], ['order' => $i]);
            }
        }
        $this->command->info('Sample jobs seeded.');

        // ── 5. Demo Candidate ─────────────────────────────────────────────────
        $candidate = User::updateOrCreate(
            ['email' => 'candidate@jobify.com'],
            [
                'name'              => 'Demo Candidate',
                'first_name'        => 'Demo',
                'last_name'         => 'Candidate',
                'password'          => bcrypt('password123'),
                'role_type'         => 'candidate',
                'status'            => 'active',
                'active'            => true,
                'auth_provider'     => 'local',
                'email_verified_at' => now(),
            ]
        );

        CandidateProfile::firstOrCreate(
            ['user_id' => $candidate->id],
            [
                'uuid'                 => Str::uuid(),
                'headline'             => 'Full Stack Developer | 4 Years Experience | PHP & React',
                'summary'              => 'Passionate software developer with 4 years of experience building scalable web applications. Skilled in PHP, Laravel, React, and cloud technologies.',
                'skills'               => ['PHP', 'Laravel', 'React', 'JavaScript', 'MySQL', 'AWS'],
                'experience'           => [
                    ['title' => 'Full Stack Developer', 'company' => 'TechStart Qatar',      'start_date' => '2022-01', 'end_date' => null,      'current' => true,  'description' => 'Building SaaS products for the MENA market.'],
                    ['title' => 'Junior Developer',     'company' => 'Digital Agency Doha',  'start_date' => '2020-06', 'end_date' => '2021-12', 'current' => false, 'description' => 'Frontend development with React and Vue.js.'],
                ],
                'education'            => [
                    ['degree' => 'B.Sc. Computer Science', 'institution' => 'Qatar University', 'year' => 2020],
                ],
                'onboarding_completed' => true,
                'completeness_score'   => 80,
            ]
        );
        $this->command->info('Candidate: candidate@jobify.com / password123  →  /candidate/dashboard');

        $this->command->newLine();
        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info('  Jobify Demo Credentials');
        $this->command->info('───────────────────────────────────────────────────');
        $this->command->info('  Platform Admin : admin@jobify.com / password123');
        $this->command->info('  Employer       : employer@jobify.com / password123');
        $this->command->info('  Candidate      : candidate@jobify.com / password123');
        $this->command->info('═══════════════════════════════════════════════════');
    }
}
