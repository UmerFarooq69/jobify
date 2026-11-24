# JOBIFY — AI-Powered Recruitment Platform
## Final Year Project Report

---

**Project Title:** Jobify — AI-Powered Recruitment Platform  
**Technology Stack:** Laravel 11 · React 19 · Inertia.js · MySQL · Google Gemini AI  
**Deployment:** Railway.app  
**Academic Year:** 2026  

---

---

# TABLE OF CONTENTS

- Chapter 1: Introduction
- Chapter 2: Design and Architecture
- Chapter 3: Development Environment
- Chapter 4: Conclusion
- References

---

---

# CHAPTER 1: INTRODUCTION

## 1.1 Project Overview

Jobify is a comprehensive, AI-powered recruitment and job placement platform developed as a full-stack web application. The platform serves as a bridge between employers seeking qualified candidates and job seekers looking for suitable career opportunities. Built using modern web technologies, Jobify goes beyond the capabilities of traditional job boards by integrating artificial intelligence at every critical stage of the recruitment process — from resume parsing and job description generation to candidate ranking and skill gap analysis.

The platform is designed with three distinct user roles: Candidates (job seekers), Employers (companies and hiring managers), and Platform Administrators. Each role has a dedicated interface tailored to their specific needs and workflows. The system eliminates the need for password-based authentication in favour of a more secure and user-friendly One-Time Password (OTP) mechanism delivered via email or phone.

Jobify represents a modern approach to recruitment technology, addressing the inefficiencies of traditional hiring processes by automating repetitive tasks, providing AI-driven insights, and offering a seamless user experience across all roles.

---

## 1.2 Problem Statement

The traditional recruitment process is plagued by inefficiencies that cost organisations significant time and money. Hiring managers spend an average of six seconds reviewing a single resume, yet still receive hundreds of applications for a single position. Candidates, on the other hand, must manually tailor their applications to each employer, repeatedly entering the same information across different platforms.

The core problems this project addresses are:

**For Employers:**
- Writing detailed, accurate job descriptions is time-consuming and inconsistent across departments.
- Reviewing large volumes of applications manually leads to bias and overlooked talent.
- Moving candidates through hiring stages (screening, interview, offer) is tracked in spreadsheets rather than a structured system.
- Communication with candidates at each stage is manual and error-prone.

**For Candidates:**
- Uploading a CV and manually re-entering the same information on every job platform is tedious.
- Candidates often do not know why they are rejected or which skills they are missing.
- There is no personalised guidance on how to improve their profile for specific job types.
- Tracking the status of multiple applications across different companies is disorganised.

**For the Industry:**
- There is no unified platform that handles the full recruitment lifecycle — from job posting to final hiring decision — with AI assistance throughout.

Jobify directly solves all of the above by providing an intelligent, structured, and automated recruitment ecosystem.

---

## 1.3 Objectives

The primary objectives of the Jobify platform are:

1. **Eliminate manual data entry** by using AI to parse uploaded CVs and automatically populate candidate profiles with structured information including work experience, education, skills, and certifications.

2. **Accelerate job posting** by allowing employers to describe a role in plain language and have AI generate a complete, professional job description including requirements, skills, employment type, and salary range.

3. **Improve candidate screening** by providing employers with an AI-generated match score (0–100) and a detailed report showing which skills a candidate possesses that match the job requirements, and which are missing.

4. **Streamline the hiring pipeline** through a customisable, stage-based recruitment pipeline where candidates move through defined stages (e.g., Applied → Screening → Interview → Offer → Hired) with automated email notifications triggered at each transition.

5. **Empower candidates** with AI tools for skill gap analysis, interview preparation, and resume generation to help them compete more effectively in the job market.

6. **Secure the platform** through OTP-based passwordless authentication, role-based access control, CSRF protection, and ownership validation on all data operations.

7. **Provide administrative oversight** through a platform administration panel for managing users, organisations, job moderation, and taxonomy.

---

## 1.4 Scope of the System

The scope of Jobify encompasses the following boundaries:

**In Scope:**
- Multi-role web application accessible via any modern web browser
- OTP authentication for all user types
- Full candidate profile management with AI-assisted CV parsing
- Job requisition management for employers with AI job description drafting
- Customisable recruitment pipeline per job
- Pipeline stage automation with email templates
- AI-powered candidate ranking per job
- Candidate-facing features: job search, save jobs, job alerts, application tracking
- AI tools: skill gap analysis, interview preparation, resume generator
- Platform admin panel: user management, job moderation, organisation management

**Out of Scope (Future Work):**
- Native mobile applications
- Video interview integration
- Real-time chat between candidates and employers
- Payment processing for premium job postings
- Integration with external job boards (LinkedIn, Indeed)

---

## 1.5 Technology Stack

Jobify is built on a carefully selected set of modern technologies that prioritise developer productivity, performance, and scalability.

| Layer | Technology | Version |
|-------|-----------|---------|
| Backend Framework | Laravel | 11.36.1 |
| Backend Language | PHP | 8.3+ |
| Frontend Library | React | 19.2.8 |
| SPA Bridge | Inertia.js | 3.7.0 |
| Build Tool | Vite | 6.0 |
| CSS Framework | Tailwind CSS | 3.4.17 |
| Database | MySQL | 8.0 |
| AI Provider | Google Gemini 2.0 Flash | — |
| Deployment | Railway.app | — |
| Version Control | Git / GitHub | — |

**Why Laravel?**
Laravel 11 provides an elegant MVC architecture, built-in authentication scaffolding, Eloquent ORM for database interactions, robust queue and job system, and an extensive ecosystem of packages. Its expressive syntax allows rapid development without sacrificing maintainability.

**Why React with Inertia.js?**
Inertia.js is the critical architectural choice that makes Jobify work without a traditional REST API. Rather than building a separate API layer and managing authentication tokens on the frontend, Inertia allows the Laravel backend to serve React components directly, passing data as page props. This means the application behaves like a Single Page Application (SPA) — fast, fluid navigation with no full page reloads — while keeping all business logic and data access on the server.

**Why Google Gemini AI?**
Google Gemini 2.0 Flash provides an OpenAI-compatible API endpoint, meaning the same interface used for OpenAI's GPT models can be used with Gemini. It offers a generous free tier suitable for a recruitment platform handling moderate traffic, and its natural language understanding is well-suited to HR tasks such as resume parsing, job description drafting, and candidate evaluation.

---

## 1.6 System Architecture Overview

Jobify follows a **Client-Server architecture** with a **Model-View-Controller (MVC)** pattern on the backend, enhanced by **Inertia.js** as a server-driven SPA adapter.

The request lifecycle works as follows:

1. A user opens the browser and navigates to any Jobify URL.
2. Laravel handles the HTTP request, authenticates the user via session, runs the appropriate controller method.
3. The controller queries the database via Eloquent models, processes business logic, and passes data to an Inertia response.
4. Inertia serialises the data as JSON page props and renders the appropriate React component.
5. On subsequent navigation (clicking a link), Inertia intercepts the request, fetches only the new page props via an XHR request, and swaps the React component — no full page reload occurs.
6. For AI operations, the Laravel backend communicates with Google Gemini's API over HTTPS and processes the response before returning it to the frontend.

---

## 1.7 User Roles and Permissions

### 1.7.1 Candidate Role

A candidate is a job seeker who registers on the platform to find and apply for jobs. The candidate role has the following capabilities:

- Register and authenticate via OTP (email or phone)
- Complete an onboarding flow that includes uploading a CV for AI parsing
- Manage their professional profile (personal information, work experience, education, skills, certifications, and uploaded resumes)
- Browse all active, approved job listings with advanced filtering
- Apply to jobs with a single click (Quick Apply)
- Save jobs to a bookmarks list for later review
- Set up job alerts based on keywords and location
- Track all submitted applications and their current pipeline stage
- Use AI-powered Skill Gap Analysis to identify missing skills for a target role
- Use AI-powered Interview Preparation to generate practice questions for a specific job
- Use the AI Resume Generator to create or improve their resume
- View their candidate dashboard with application statistics

### 1.7.2 Employer Role (Company Admin)

An employer is a company representative with the authority to post jobs and manage the full recruitment pipeline. The employer role has the following capabilities:

- Register and authenticate via OTP
- Complete company setup (name, logo, industry, size, location, website, description)
- Manage departments within their organisation
- Create and manage job requisitions (job postings) with AI-assisted generation
- View and manage all received applications per job through a Kanban-style pipeline
- Review individual candidates including their CV, profile, AI match score, matched skills, missing skills, and AI-generated summary
- Add screening notes and assign a star rating (1–5) to candidates
- Move candidates between pipeline stages
- Configure pipeline automations — automatic email triggers when a candidate enters a specific stage
- Manage custom email templates for each pipeline stage
- Use AI to automatically rank all candidates for a job by match score
- View employer dashboard with hiring statistics

### 1.7.3 Platform Administrator

The platform administrator oversees the entire Jobify ecosystem and has the following capabilities:

- Access the admin dashboard with platform-wide statistics
- Manage all registered users (candidates and employers)
- Review and moderate job postings (approve or reject)
- Manage registered organisations
- Manage job taxonomy (job functions, job types)
- Monitor platform activity

---

## 1.8 Authentication System

### 1.8.1 OTP-Based Passwordless Authentication

Jobify uses a passwordless authentication system based on One-Time Passwords (OTP). This design choice eliminates the security risks associated with password management — such as weak passwords, password reuse, and credential stuffing attacks — while also simplifying the user experience.

**The OTP Authentication Flow:**

1. **Identifier Entry:** The user enters their email address or phone number and selects their role (candidate or employer).
2. **OTP Generation:** The server generates a 6-digit OTP, stores it with an expiry timestamp, and sends it via email using Laravel's Mailable system.
3. **OTP Verification:** The user enters the 6-digit code. The server validates it against the stored value and checks it has not expired.
4. **Session Creation:** Upon successful verification, a Laravel session is created for the user. The server returns a fresh CSRF token to the client, which immediately updates the meta tag in the browser's DOM.
5. **Role-Based Redirect:** New users are redirected to their respective setup or onboarding flow. Returning users are redirected directly to their dashboard.

**Security Considerations:**
- OTP routes (`/otp/send`, `/otp/verify`) are exempt from CSRF verification because they are the entry point for session creation — it is architecturally impossible to have a valid CSRF token before a session exists.
- After OTP verification, the server returns a fresh CSRF token which the client uses for all subsequent requests.
- OTPs expire after a configurable time window, preventing replay attacks.
- The system is protected against brute force through Laravel's built-in rate limiting.

### 1.8.2 Role-Based Access Control

Each user has a `role_type` field in the users table that is set at registration time. Laravel middleware checks this role on every protected route:

- `auth` middleware: ensures the user is logged in
- `admin` middleware: ensures the user has the platform administrator role
- Candidate routes are prefixed with `/candidate/` and employer routes with `/employer/`

Additionally, all data operations include ownership validation. For example, an employer can only view, edit, or delete jobs belonging to their own organisation. A candidate can only delete their own saved jobs and job alerts.

---

## 1.9 Candidate Features — Detailed Description

### 1.9.1 Candidate Registration and Onboarding

When a new candidate registers, they are guided through a multi-step onboarding flow before accessing the platform.

**Step 1 — Identifier:**
The candidate enters their email address or phone number. The system sends a 6-digit OTP to the provided contact.

**Step 2 — OTP Verification:**
The candidate enters the 6-digit OTP displayed in their email. The input automatically advances focus between digits for a smooth user experience.

**Step 3 — CV Upload:**
New candidates are prompted to upload their CV (PDF, DOC, or DOCX format, up to 5MB). The file is uploaded to the server and immediately processed by the AI Resume Parser.

**Step 4 — AI-Parsed Preview:**
The system displays the extracted information from the CV including name, headline, professional summary, work experience, education history, and skills. The candidate can review this data before confirming.

**Step 5 — Profile Creation:**
The confirmed parsed data is saved to the candidate's profile. The CV file itself is stored in the resumes table linked to the candidate's profile.

After completing onboarding, the candidate is redirected to their dashboard.

**[SCREENSHOT: Candidate onboarding — OTP input step]**

**[SCREENSHOT: Candidate onboarding — CV upload step]**

**[SCREENSHOT: Candidate onboarding — AI parsed data preview]**

### 1.9.2 Candidate Dashboard

The candidate dashboard provides an at-a-glance overview of the candidate's recruitment activity.

**Dashboard Components:**
- **Statistics Row:** Total applications submitted, number of interviews, number of saved jobs
- **Recent Applications:** A list of the 5 most recent job applications with company logo, job title, current pipeline stage, and application date
- **AI-Powered Recommendations:** Personalised job recommendations based on the candidate's profile (future enhancement placeholder)
- **Quick Links:** Direct navigation to profile, saved jobs, skill gap analysis, and interview preparation tools

**[SCREENSHOT: Candidate dashboard]**

### 1.9.3 Candidate Profile Management

The profile page allows candidates to maintain a comprehensive professional profile.

**Profile Sections:**
- **Personal Information:** Full name, headline (e.g., "Senior Laravel Developer"), professional summary, phone number
- **Work Experience:** Each entry includes company name, job title, start date, end date (or "Present"), and description of responsibilities
- **Education:** Institution name, degree, field of study, start and end dates
- **Skills:** A list of professional skills displayed as tags
- **Certifications:** Certification name, issuing organisation, and date
- **Resumes:** All uploaded CV files with the ability to designate one as primary

The profile also shows a **Completeness Score** — a percentage calculated based on how many sections have been filled in — encouraging candidates to complete their profiles for better job matches.

**[SCREENSHOT: Candidate profile page]**

### 1.9.4 Job Search and Browsing

The job browsing page (`/find-jobs`) is the primary interface for candidates to discover job opportunities.

**Layout:**
The page uses a split-panel layout: the left panel shows a scrollable list of job cards, while the right panel shows the full detail of the selected job. On mobile, the layout switches to a single-panel view with back navigation.

**Search:**
Candidates can search by job title or keyword and filter by location using a prominent search bar at the top of the page.

**Filter Bar:**
Below the search bar, candidates can refine results by:
- Sort order (Most relevant, Newest, Highest salary, Lowest salary)
- Date posted (Last 24 hours, Last week, Last month, Last 3 months)
- Experience level (Entry, Mid, Senior, Executive)
- Job type (Full Time, Part Time, Contract, Freelance, Internship)
- Job shift (Day, Night, Morning, Flexible)

**Job Card (Left Panel):**
Each job card displays:
- Company logo or initial
- Job title
- Company name and location (highlighted in brand colour)
- Employment type tag
- Applied/Quick Apply status badge
- Time since posting

**Job Detail (Right Panel):**
Clicking a job card loads the full detail panel showing:
- Company logo and name
- Job title, location, employment type, and salary range
- Apply Now button (or "Applied" if already applied)
- Save Job bookmark button
- View Full Details link
- AI-generated Job Summary (expandable accordion)
- Full job description
- Key responsibilities and requirements
- Required skills as pill tags
- About the Company section

**[SCREENSHOT: Job search page — split panel view]**

**[SCREENSHOT: Job detail panel with Apply and Save buttons]**

### 1.9.5 Job Application

Applying for a job is designed to be as frictionless as possible. When a candidate clicks "Apply Now":

1. If not logged in, the authentication modal opens.
2. If logged in but onboarding not completed, the candidate is redirected to complete their profile.
3. If the candidate has already applied, the button shows "✓ Applied" in grey (disabled).
4. On successful application, the system creates a `CandidateApplication` record, links the candidate's primary resume, sets the status to "applied", assigns the application to the first pipeline stage of the job, and records the `applied_at` timestamp.

### 1.9.6 Save Jobs

Candidates can bookmark jobs for later review by clicking the bookmark icon on any job detail panel. The icon fills with the brand magenta colour when saved and returns to outline when unsaved. Saved jobs can be accessed from the Saved Jobs page in the candidate dashboard navigation.

### 1.9.7 Job Alerts

Candidates can create job alerts based on keywords and location. When a matching job is posted on the platform, the system notifies the candidate via email. Candidates can manage (create and delete) their alerts from the Job Alerts page.

### 1.9.8 Application Tracking

The Applications page shows all jobs the candidate has applied to with:
- Company logo and job title
- Current pipeline stage name
- Application status
- Date applied

### 1.9.9 AI Skill Gap Analysis

The Skill Gap Analysis tool helps candidates understand how their current profile compares to their target role. The candidate describes their target role, and the AI analyses their existing skills against typical requirements for that role, returning:
- Skills they already possess that are relevant
- Skills they are missing that are commonly required
- Recommendations for how to bridge the gap

### 1.9.10 AI Interview Preparation

The Interview Preparation tool generates a customised set of interview questions for a specific job or role. The candidate enters a job title or description and receives a structured list of:
- Technical questions relevant to the role
- Behavioural questions (STAR format)
- Suggested answers and tips for each question

**[SCREENSHOT: Interview preparation tool]**

### 1.9.11 AI Resume Generator

The Resume Generator tool helps candidates create or improve their resume content. The candidate provides their career details and the AI generates professional, well-structured resume content optimised for the target role.

---

## 1.10 Employer Features — Detailed Description

### 1.10.1 Employer Registration and Company Setup

New employers go through a company setup flow after OTP verification:

1. **Company Name and Industry:** Basic organisation details
2. **Company Size:** Number of employees (ranges: 1–10, 11–50, 51–200, 201–500, 500+)
3. **Location:** City and country
4. **Contact Details:** Website URL, phone number, email
5. **Description:** A paragraph describing the company for candidates to read on job postings
6. **Logo Upload:** Company logo (displayed on all job postings and in the platform header)

**[SCREENSHOT: Employer company setup page]**

### 1.10.2 Employer Dashboard

The employer dashboard is the central hub for managing recruitment activity.

**Dashboard Components:**
- **Statistics Cards:** Total active jobs, total applications received, candidates in interview stage, positions filled
- **Recent Applications:** Latest candidate applications across all jobs
- **Active Jobs:** A list of currently open positions with application counts
- **Quick Actions:** Post new job, view pipeline, manage settings

**[SCREENSHOT: Employer dashboard]**

### 1.10.3 Job Requisition Management

Employers manage all job postings from the Jobs section.

**Job Listing Page:**
All jobs are shown in a table with columns for title, department, employment type, salary range, status, number of applicants, and actions (edit, view pipeline, delete).

**Post New Job:**
The job creation form includes:
- Job Title
- Department (selected from employer's departments)
- Employment Type (Full Time, Part Time, Contract, Freelance, Internship)
- Location
- Salary Range (minimum, maximum, currency)
- Job Description (rich text area)
- Requirements
- Required Skills (tag input)
- Urgent Hire toggle

**AI Job Description Generation:**
Employers can skip filling in the form manually by clicking "Generate with AI." They type a brief description of the role (e.g., "We need a senior Laravel developer for our fintech startup in Doha") and the AI generates a complete job posting including title, summary, description, requirements, skills, employment type, and salary range.

The AI prompt is designed to return structured JSON that maps directly to the job form fields, allowing the employer to review and edit before saving.

**[SCREENSHOT: Post new job form]**

**[SCREENSHOT: AI job description generation]**

### 1.10.4 Department Management

Employers can create departments (e.g., Engineering, Marketing, Sales, HR) to organise their job postings. Each department belongs to the employer's organisation, and jobs are assigned to departments for better organisation.

### 1.10.5 Recruitment Pipeline

The pipeline is the core employer workflow feature. Each job has its own configurable pipeline with stages that represent steps in the hiring process.

**Default Stages (customisable):**
- Applied
- Screening
- Interview
- Assessment
- Offer
- Hired
- Rejected

**Pipeline View:**
The pipeline is displayed as a Kanban-style board where each column represents a stage, and cards represent candidate applications. Employers can drag candidates between stages or use a dropdown to move them. The count of candidates in each stage is shown at the column header.

**[SCREENSHOT: Pipeline Kanban view]**

### 1.10.6 Application Review

Clicking a candidate card in the pipeline opens a comprehensive review panel.

**Review Panel Components:**

**Stage Progress Stepper:**
A full-width progress bar at the top shows all pipeline stages as connected steps. The current stage is highlighted in the brand gradient. The candidate can be moved to the next or previous stage from this stepper.

**Candidate Information:**
- Full name, headline, and location
- Contact details (email, phone)
- Profile completeness score
- CV download link

**AI Match Analysis:**
When AI ranking has been run for the job, this section shows:
- Circular score indicator (0–100) with colour coding (green = high match, yellow = medium, red = low)
- AI-generated summary paragraph explaining the candidate's fit
- **Matched Skills:** Green pill tags showing skills the candidate has that match job requirements
- **Missing Skills:** Red pill tags showing skills required by the job that the candidate lacks

**Screening Notes:**
Employers can add private notes about the candidate and assign a star rating (1 to 5 stars) for quick screening reference. Notes and ratings are saved immediately.

**[SCREENSHOT: Application review panel — stage stepper]**

**[SCREENSHOT: Application review panel — AI match analysis with matched/missing skills]**

**[SCREENSHOT: Application review panel — notes and star rating]**

### 1.10.7 AI Candidate Ranking

The AI ranking feature analyses all candidates who applied to a specific job and ranks them by their suitability.

**How it works:**
1. The employer clicks "Rank with AI" on the job pipeline page.
2. The system retrieves all candidate profiles (skills, experience, education, headline) and the job requirements (title, description, skills, requirements).
3. This data is sent to the Gemini AI with a structured prompt requesting a ranked list.
4. The AI returns a JSON array sorted by score (0–100) with:
   - Candidate ID
   - Match score
   - Reasoning paragraph (2–3 sentences)
   - Matched skills array
   - Missing skills array
5. Each candidate's `match_score` and full `match_report` (including summary, matched skills, missing skills) are saved to the database.
6. The pipeline view updates to show candidate cards sorted by score.

### 1.10.8 Pipeline Automations

Pipeline automations allow employers to configure automatic email actions triggered when a candidate enters a specific pipeline stage.

**Configuration:**
For each stage in the pipeline, an employer can set:
- **Trigger:** "On enter stage" (candidate moves into this stage)
- **Action:** "Send email"
- **Email Template:** Which template to send

The email is dispatched synchronously using `Mail::send()` ensuring immediate delivery without requiring a background queue worker.

**Email Templates:**
Employers manage a library of email templates for each stage:
- Template name
- Subject line
- Email body (with placeholders for candidate name, job title, company name)

**[SCREENSHOT: Pipeline automations configuration page]**

---

## 1.11 Platform Administration Features

### 1.11.1 Admin Dashboard

The admin panel provides a platform-wide view of system activity including total users, organisations, jobs, and applications.

### 1.11.2 Job Moderation

All jobs posted by employers default to a `pending` moderation status. The admin can review and either approve (making the job visible in the public job feed) or reject the posting. This ensures quality control of job listings on the platform.

### 1.11.3 User Management

Admins can view all registered users, their role type, registration date, and account status.

### 1.11.4 Organisation Management

Admins can view and manage all registered employer organisations on the platform.

### 1.11.5 Job Taxonomy

The admin manages a centralised taxonomy of:
- **Job Functions:** Categories such as Engineering, Marketing, Finance, Healthcare
- **Job Types:** Employment type categories used across the platform

**[SCREENSHOT: Admin job moderation panel]**

---

## 1.12 AI Integration Architecture

### 1.12.1 LlmService

The core AI abstraction layer is `LlmService`, a Laravel service class that handles all communication with the AI provider. It uses Laravel's HTTP client to make POST requests to the Gemini API's OpenAI-compatible endpoint:

```
https://generativelanguage.googleapis.com/v1beta/openai/chat/completions
```

The service accepts an array of messages in the standard `[{role, content}]` format and returns either a text response or a parsed JSON object depending on the method called (`chat()` for text, `chatJson()` for structured JSON).

### 1.12.2 ResumeParserService

The Resume Parser Service extracts structured information from uploaded CV files. It:
1. Reads the CV file from the server's storage path
2. Converts the content to base64 for transmission
3. Sends the file content to the AI with a prompt requesting structured extraction
4. Returns a parsed JSON object with: `name`, `email`, `phone`, `headline`, `summary`, `experience` (array), `education` (array), `skills` (array), `certifications` (array)

### 1.12.3 JobAiService

The Job AI Service handles two AI operations:

**Job Drafting (`draftJob`):**
Takes a brief employer prompt and company name, returns a complete job posting as structured JSON.

**Candidate Ranking (`rankCandidates`):**
Takes an array of candidate profiles and job details, returns a ranked array with scores, reasoning, matched skills, and missing skills.

---

## 1.13 Security Implementation

### 1.13.1 CSRF Protection

Jobify uses Laravel's built-in CSRF (Cross-Site Request Forgery) protection for all state-changing HTTP requests. The CSRF token is embedded in a meta tag in the HTML head and included in all Inertia.js requests automatically.

After OTP login, the server returns a fresh CSRF token in the JSON response, which the client immediately writes to the meta tag, ensuring seamless continuation of secure requests after session creation.

### 1.13.2 Route-Level Authentication

All sensitive routes are protected by the `auth` middleware, ensuring unauthenticated requests are redirected to the appropriate login flow. Administrative routes additionally check for the `admin` role.

### 1.13.3 Ownership Validation

Every data operation validates that the authenticated user owns the resource being accessed or modified. For example:
- An employer can only edit jobs belonging to their own organisation
- A candidate can only delete their own saved jobs and alerts
- Pipeline data is scoped to the job's owning organisation

### 1.13.4 Input Validation

All form submissions are validated on the server using Laravel's `Request::validate()` or dedicated Form Request classes before any database operation occurs.

### 1.13.5 Proxy Trust Configuration

Since Jobify is deployed behind Railway's load balancer (which terminates HTTPS), the `TrustProxies` middleware is configured to trust all proxy headers. This ensures HTTPS URLs are generated correctly and that the `X-Forwarded-Proto` header is respected for secure redirects.

---

---

# CHAPTER 2: DESIGN AND ARCHITECTURE

## 2.1 System Architecture

Jobify uses a **Client-Server architecture** with the **Model-View-Controller (MVC)** design pattern. The key architectural differentiator is the use of **Inertia.js** as an adapter layer between the Laravel MVC backend and the React frontend, enabling a seamless Single Page Application experience without a traditional REST API.

```
┌─────────────────────────────────────────────────────────────┐
│                        CLIENT BROWSER                        │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │                  React Components                    │   │
│  │  (Jobs.jsx, Dashboard.jsx, Review.jsx, etc.)        │   │
│  │                                                     │   │
│  │  ┌─────────────┐    ┌────────────────────────────┐  │   │
│  │  │  Inertia.js │    │   Tailwind CSS Styling     │  │   │
│  │  │  Client     │    │   Brand: #9E0059 / #38009A │  │   │
│  │  └─────────────┘    └────────────────────────────┘  │   │
│  └─────────────────────────────────────────────────────┘   │
└──────────────────────────────┬──────────────────────────────┘
                               │ HTTPS / XHR (Inertia Protocol)
┌──────────────────────────────▼──────────────────────────────┐
│                        RAILWAY.APP SERVER                    │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │                 Laravel 11 Application               │   │
│  │                                                     │   │
│  │  Routes → Middleware → Controllers → Services       │   │
│  │                                                     │   │
│  │  ┌──────────────┐   ┌────────────────────────────┐  │   │
│  │  │  Eloquent    │   │   LlmService               │  │   │
│  │  │  ORM Models  │   │   JobAiService             │  │   │
│  │  └──────┬───────┘   │   ResumeParserService      │  │   │
│  │         │           └──────────────┬─────────────┘  │   │
│  └─────────┼──────────────────────────┼────────────────┘   │
│            │                          │ HTTPS API Call      │
│  ┌─────────▼─────────┐   ┌────────────▼─────────────────┐  │
│  │   MySQL Database  │   │   Google Gemini 2.0 Flash    │  │
│  │   (Railway)       │   │   API                        │  │
│  └───────────────────┘   └──────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
```

## 2.2 MVC Layer Breakdown

**Models (Eloquent ORM):**
Models represent database tables and their relationships. Key models include `User`, `CandidateProfile`, `Organization`, `JobRequisition`, `CandidateApplication`, `PipelineStage`, `PipelineAutomation`, `Resume`, `SavedJob`, `JobAlert`.

**Views (React Components via Inertia):**
Instead of traditional Blade templates, Jobify uses React components as views. Inertia bridges the gap: the controller calls `Inertia::render('ComponentName', $data)` and the corresponding React component receives `$data` as props.

**Controllers:**
Laravel controllers handle HTTP requests, enforce business logic, perform database operations, and return Inertia responses. The main controllers are:
- `CandidateController` — all candidate-facing operations
- `CompanyController` — all employer-facing operations
- `PublicJobsController` — public job browsing
- `OtpController` — authentication
- `QAdminController` — platform administration

## 2.3 Database Entity Relationship Description

The database consists of the following primary entities and relationships:

**User** has one **CandidateProfile** (for candidate users) OR belongs to one **Organization** (for employer users).

**CandidateProfile** has many **CandidateApplications**, many **SavedJobs**, many **JobAlerts**, and many **Resumes**.

**Organization** has many **JobRequisitions** and many **Departments**.

**JobRequisition** has many **CandidateApplications**, many **PipelineStages**, and many **PipelineAutomations**.

**PipelineStage** has many **CandidateApplications** and has many **PipelineAutomations**.

**CandidateApplication** belongs to one **CandidateProfile**, one **JobRequisition**, one **PipelineStage**, and one **Resume**.

## 2.4 Key Design Patterns

**Service Layer Pattern:**
AI operations are abstracted into dedicated service classes (`LlmService`, `JobAiService`, `ResumeParserService`) rather than placed directly in controllers. This promotes separation of concerns and makes the AI provider swappable.

**Repository-Free Active Record:**
Jobify uses Laravel's Eloquent ORM as an Active Record implementation. Models contain their own query scopes (e.g., `active()`, `approved()`) keeping query logic close to the data it concerns.

**Observer / Event-Driven Automation:**
Pipeline automations are triggered synchronously when a candidate's stage changes. The `dispatchAutomations()` method in `CompanyController` checks for active automations on the new stage and dispatches emails using Laravel Mailables.

## 2.5 Use Case Summary

### Candidate Use Cases:
- Register via OTP
- Upload and parse CV
- Complete profile
- Browse and search jobs
- Apply to a job
- Save a job
- Create job alert
- View application status
- Run skill gap analysis
- Generate interview questions
- Generate resume

### Employer Use Cases:
- Register via OTP
- Set up company
- Create department
- Post a job (manual or AI-assisted)
- View all applications in pipeline
- Review candidate application
- Move candidate between stages
- Add notes and star rating
- Run AI candidate ranking
- Configure pipeline automations
- Manage email templates

### Admin Use Cases:
- View platform statistics
- Approve / reject job postings
- Manage users
- Manage organisations
- Manage taxonomy

## 2.6 Deployment Architecture

```
GitHub Repository (UmerFarooq69/jobify)
         │
         │  git push → auto-deploy trigger
         ▼
   Railway.app Platform
         │
         ├── Laravel App Service
         │   - PHP 8.3 + Node.js 20
         │   - Nixpacks build (composer install, npm ci, npm run build)
         │   - Start: php artisan migrate --force && php artisan serve
         │   - Domain: jobify-production-019b8bd2.up.railway.app
         │
         └── MySQL Database Service
             - mysql.railway.internal (private network)
             - Database: railway
             - Persistent volume: mysql-volume
```

Continuous deployment is configured: every push to the `main` branch on GitHub automatically triggers a new Railway build and deployment. This enables a streamlined development workflow where code changes are live in 2–3 minutes after pushing.

---

---

# CHAPTER 3: DEVELOPMENT ENVIRONMENT

## 3.1 Prerequisites

Before setting up the Jobify project, ensure the following software is installed on your development machine:

| Software | Minimum Version | Purpose |
|----------|----------------|---------|
| PHP | 8.3 | Backend runtime |
| Composer | 2.x | PHP dependency manager |
| Node.js | 20.x | Frontend build tool runtime |
| npm | 10.x | Node package manager |
| MySQL | 8.0 | Database server |
| Git | 2.x | Version control |

**Installing PHP on Windows:**
Download PHP 8.3 from https://windows.php.net/download/ and add it to your system PATH. Enable the following extensions in `php.ini`:
- `extension=pdo_mysql`
- `extension=mbstring`
- `extension=openssl`
- `extension=fileinfo`
- `extension=zip`
- `extension=gd`
- `extension=curl`

Alternatively, install **Laragon** (https://laragon.org/) which bundles PHP, MySQL, and Composer in a single Windows installer.

**Installing Node.js:**
Download the LTS version from https://nodejs.org/. npm is included automatically.

---

## 3.2 Step 1 — Clone the Repository

Open a terminal and run:

```bash
git clone https://github.com/UmerFarooq69/jobify.git
cd jobify
```

---

## 3.3 Step 2 — Install PHP Dependencies

```bash
composer install
```

This installs all backend packages listed in `composer.json` including Laravel framework, Inertia.js adapter, and other dependencies. The packages are installed into the `vendor/` directory.

Expected output: `Generating optimized autoload files` followed by package count.

---

## 3.4 Step 3 — Install Node.js Dependencies

```bash
npm install
```

This installs all frontend packages listed in `package.json` including React, Inertia.js React adapter, Vite, and Tailwind CSS. Packages are installed into the `node_modules/` directory.

---

## 3.5 Step 4 — Configure Environment Variables

Copy the example environment file:

```bash
cp .env.example .env
```

Open `.env` in a text editor and configure the following values:

**Application Settings:**
```
APP_NAME=Jobify
APP_ENV=local
APP_KEY=                    # Will be generated in Step 5
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
```

**Database Settings:**
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jobify
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

**Mail Settings (for OTP delivery):**
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="Jobify"
```

For local testing, you can use `MAIL_MAILER=log` which writes emails to `storage/logs/laravel.log` instead of sending them, allowing you to copy OTP codes from the log file.

**AI Settings:**
```
PARSER_BASE_URL=https://generativelanguage.googleapis.com/v1beta/openai
PARSER_API_KEY=your_gemini_api_key
PARSER_MODEL=gemini-2.0-flash
```

Obtain a free Gemini API key from https://aistudio.google.com/app/apikey.

---

## 3.6 Step 5 — Generate Application Key

```bash
php artisan key:generate
```

This generates a unique encryption key and writes it to `APP_KEY` in `.env`. This key is used for encrypting sessions, cookies, and other sensitive data. Never share this key publicly.

---

## 3.7 Step 6 — Create the Database

Open MySQL client (MySQL Workbench, phpMyAdmin, or command line) and create the database:

```sql
CREATE DATABASE jobify CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

---

## 3.8 Step 7 — Run Database Migrations

```bash
php artisan migrate
```

This creates all database tables according to the migration files in `database/migrations/`. The command runs all pending migrations in order.

To also seed the database with sample data (if seeders are configured):

```bash
php artisan migrate --seed
```

---

## 3.9 Step 8 — Create Storage Symlink

```bash
php artisan storage:link
```

This creates a symbolic link from `public/storage` to `storage/app/public`, making uploaded files (CV files, company logos, user avatars) accessible via the web server. Without this step, file uploads will not be visible in the browser.

---

## 3.10 Step 9 — Build Frontend Assets

**For production build:**
```bash
npm run build
```

This compiles and bundles all React components, Tailwind CSS, and JavaScript into optimised files in the `public/build/` directory.

**For development (with hot reload):**
```bash
npm run dev
```

This starts the Vite development server on port 5173 with Hot Module Replacement (HMR) — changes to React components or CSS are reflected in the browser instantly without a page reload.

---

## 3.11 Step 10 — Start the Development Server

```bash
php artisan serve
```

This starts the Laravel development server at `http://127.0.0.1:8000`.

> **Important Note:** For local development, do not use `PHP_CLI_SERVER_WORKERS` greater than 1. Multiple PHP workers do not share session state, which causes CSRF token mismatches between OTP-related requests. Run the server with a single worker (the default).

Open your browser and navigate to `http://127.0.0.1:8000` to access the application.

---

## 3.12 Development Workflow Summary

The complete startup sequence for daily development:

```bash
# Terminal 1 — Backend server
php artisan serve

# Terminal 2 — Frontend dev server (optional, for hot reload)
npm run dev
```

If using `npm run dev`, the Vite dev server proxies asset requests and provides HMR. If using `npm run build`, assets are served statically from `public/build/`.

---

## 3.13 Railway Deployment (Production)

The project includes a `nixpacks.toml` file at the repository root that configures the Railway build process automatically.

**Step 1:** Create an account at https://railway.app and connect your GitHub account.

**Step 2:** Create a new project → Deploy from GitHub repo → Select `jobify`.

**Step 3:** Add a MySQL database service to the project.

**Step 4:** Configure the following environment variables in the jobify service:

```
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:your_generated_key
APP_URL=https://your-railway-domain.up.railway.app
DB_CONNECTION=mysql
DB_HOST=mysql.railway.internal
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
SESSION_DRIVER=database
FILESYSTEM_DISK=public
MAIL_MAILER=log
PARSER_BASE_URL=https://generativelanguage.googleapis.com/v1beta/openai
PARSER_API_KEY=your_gemini_api_key
PARSER_MODEL=gemini-2.0-flash
```

**Step 5:** Railway automatically builds and deploys. Each subsequent push to the `main` branch triggers a new deployment automatically.

---

## 3.14 Environment Variables Reference

| Variable | Description | Example Value |
|----------|-------------|---------------|
| APP_KEY | Laravel encryption key | base64:xyz... |
| APP_URL | Full application URL | http://127.0.0.1:8000 |
| DB_HOST | MySQL host | 127.0.0.1 |
| DB_DATABASE | Database name | jobify |
| DB_USERNAME | Database user | root |
| DB_PASSWORD | Database password | secret |
| MAIL_MAILER | Mail driver | smtp or log |
| MAIL_HOST | SMTP server | smtp.gmail.com |
| PARSER_BASE_URL | Gemini API base URL | https://generativelanguage... |
| PARSER_API_KEY | Gemini API key | AIza... |
| PARSER_MODEL | AI model name | gemini-2.0-flash |
| SESSION_DRIVER | Session storage driver | database or file |
| FILESYSTEM_DISK | File storage disk | public |

---

---

# CHAPTER 4: CONCLUSION

## 4.1 Project Summary

Jobify successfully delivers a comprehensive, AI-augmented recruitment platform that addresses the core inefficiencies of traditional hiring processes. The platform integrates modern web technologies with artificial intelligence to create a system that is functional, scalable, and genuinely useful to both job seekers and employers.

The project demonstrates several significant technical achievements:

**Passwordless Authentication at Scale:** The OTP-based authentication system eliminates password management complexities while maintaining security. The careful handling of CSRF tokens across session boundaries — returning a fresh token after OTP verification and updating the DOM meta tag client-side — solves a nuanced problem that arises in multi-worker server environments.

**AI Integration Throughout the Workflow:** Rather than treating AI as a single feature, Jobify embeds artificial intelligence at every meaningful touchpoint: CV parsing at registration, job description generation at posting, candidate ranking in the pipeline, and skill development tools for candidates. Using Google Gemini's OpenAI-compatible endpoint enabled rapid integration with a production-quality AI model at zero cost.

**Inertia.js as Architectural Bridge:** The choice of Inertia.js proved highly effective, eliminating the need for a separate API layer while still delivering a smooth single-page application experience. This significantly reduced development complexity — there are no API tokens, no CORS configuration, and no duplicated validation logic between frontend and backend.

**Automated Recruitment Workflow:** The pipeline automation system — where email notifications are triggered automatically as candidates move between stages — demonstrates how even a simple trigger-action model can significantly reduce manual work for hiring teams.

## 4.2 Challenges Faced and Solutions

### Challenge 1: CSRF Token Mismatch with Multi-Worker PHP Server

**Problem:** When running the PHP development server with multiple workers (`PHP_CLI_SERVER_WORKERS=4`), different workers handle different requests without sharing session state. This caused CSRF token validation to fail on OTP routes because the token generated during page load was stored in a different worker's session than the one processing the POST request.

**Solution:** OTP-related routes were exempted from CSRF verification in `bootstrap/app.php`. This is architecturally sound because OTP itself provides the security guarantee — the user must possess the OTP sent to their email/phone to authenticate. Additionally, after successful OTP verification, the server returns a fresh CSRF token in the response body, and the client immediately updates the meta tag in the DOM, ensuring all subsequent requests use the correct token.

### Challenge 2: React Component Remounting in Pipeline View

**Problem:** Defining a component function (e.g., `SortTh`) inside a parent component causes React to treat it as a new component type on every render, leading to full unmounting and remounting. This manifested as a blank screen on the pipeline page whenever state changed.

**Solution:** All component definitions were moved outside their parent components, ensuring React treats them as stable component types across renders.

### Challenge 3: Inertia Router API Mismatch

**Problem:** `router.delete(url, {}, {onSuccess: ...})` was being called with 3 arguments, but Inertia's `router.delete` only accepts 2 — the URL and an options object. This caused the `onSuccess` callback to never fire.

**Solution:** Merged the options into a single object: `router.delete(url, {onSuccess: ...})`.

### Challenge 4: File Upload Chain with AI Parsing

**Problem:** After uploading a CV and parsing it with AI, the file path needed to be threaded through the multi-step onboarding flow to be saved to the resumes table on completion. The initial implementation lost the file path between steps.

**Solution:** The `parseResume` API response includes the stored `file_path`. The frontend stores this in state and includes it in the final `completeOnboarding` POST request, where the backend saves it to the resumes table.

### Challenge 5: AI Response Structure

**Problem:** The initial AI ranking only saved a match score and reasoning text. The match analysis UI needed to show matched and missing skills as separate lists.

**Solution:** The AI prompt was updated to request structured JSON with explicit `matched_skills` and `missing_skills` arrays. The database `match_report` column (cast as JSON) stores the full report including summary, matched skills, and missing skills, all of which are displayed in the review panel.

## 4.3 Future Work

The following enhancements are planned for future iterations of Jobify:

### 4.3.1 Native Mobile Application
Develop React Native mobile applications for iOS and Android that provide candidates with push notifications for job alerts, application status updates, and interview reminders. The existing Laravel backend can serve as the API layer with minimal modification.

### 4.3.2 Real-Time Notifications
Integrate Laravel Echo with WebSockets (via Reverb or Pusher) to enable real-time notifications within the web application. Candidates would receive instant updates when their application moves to a new stage, and employers would receive instant notifications when new applications arrive.

### 4.3.3 Video Interview Integration
Add an integrated video interview module allowing employers to schedule and conduct video interviews directly within the platform. This would include recording capabilities, shareable interview links, and AI-powered interview transcription and analysis.

### 4.3.4 Advanced AI Features
- **Salary Prediction:** Use historical job data and candidate experience levels to suggest competitive salary ranges for new job postings.
- **Culture Fit Scoring:** Analyse the alignment between a candidate's professional values (derived from their career history and application answers) and the company's described culture.
- **Bias Detection:** Flag job descriptions that contain language known to discourage diverse candidates.

### 4.3.5 External Job Board Integration
Integrate with LinkedIn Jobs API and Indeed Publisher API to allow employers to post jobs to multiple platforms simultaneously and import applications back into Jobify's pipeline.

### 4.3.6 Payment and Premium Features
Implement a payment system (Stripe) for premium features:
- Featured job listings with higher visibility
- Priority AI ranking credits
- Advanced analytics and reporting for employers
- Unlimited pipeline automations

### 4.3.7 Advanced Analytics Dashboard
Provide employers with detailed recruitment analytics:
- Time-to-hire metrics per job and department
- Application funnel conversion rates (applied → screening → interview → hired)
- Source tracking (which job boards or channels brought the best candidates)
- Diversity and inclusion metrics

### 4.3.8 Multi-Language Support
Expand the platform to support Arabic, particularly for the Gulf Cooperation Council (GCC) market where both Arabic and English are used in professional contexts. This includes right-to-left (RTL) layout support using Tailwind CSS's RTL utilities.

### 4.3.9 Employer Collaboration Tools
Allow multiple team members from the same organisation to collaborate on hiring decisions:
- Multiple reviewers per application with individual notes
- Voting/approval workflows for hiring decisions
- Role-based permissions within an organisation (recruiter, hiring manager, executive reviewer)

### 4.3.10 AI-Powered Candidate Matching Notifications
Implement a background job system that runs nightly to compare all candidate profiles against all active job requisitions, automatically notifying candidates of jobs they are well-suited for and notifying employers of unreviewed candidates with high match scores.

---

---

# REFERENCES

## Frameworks and Libraries

1. Laravel Documentation. (2024). *Laravel 11.x Documentation*. Retrieved from https://laravel.com/docs/11.x

2. Meta Open Source. (2024). *React 19 Documentation*. Retrieved from https://react.dev

3. Inertia.js. (2024). *Inertia.js — The Modern Monolith*. Retrieved from https://inertiajs.com

4. Tailwind Labs. (2024). *Tailwind CSS Documentation v3*. Retrieved from https://tailwindcss.com/docs

5. Vite. (2024). *Vite — Next Generation Frontend Tooling*. Retrieved from https://vitejs.dev

6. Google. (2024). *Gemini API Documentation — OpenAI Compatibility*. Retrieved from https://ai.google.dev/gemini-api/docs/openai

## Deployment

7. Railway. (2024). *Railway Documentation — Nixpacks*. Retrieved from https://docs.railway.app

8. Nixpacks. (2024). *Nixpacks Build System*. Retrieved from https://nixpacks.com

## Authentication and Security

9. OWASP. (2024). *OTP Authentication Best Practices*. Retrieved from https://cheatsheetseries.owasp.org/cheatsheets/Multifactor_Authentication_Cheat_Sheet.html

10. OWASP. (2024). *Cross-Site Request Forgery (CSRF) Prevention Cheat Sheet*. Retrieved from https://cheatsheetseries.owasp.org/cheatsheets/Cross-Site_Request_Forgery_Prevention_Cheat_Sheet.html

11. Laravel. (2024). *Laravel CSRF Protection*. Retrieved from https://laravel.com/docs/11.x/csrf

## AI and Machine Learning in Recruitment

12. Raghavan, M., Barocas, S., Kleinberg, J., & Levy, K. (2020). *Mitigating Bias in Algorithmic Hiring: Evaluating Claims and Practices*. Proceedings of the 2020 Conference on Fairness, Accountability, and Transparency (FAccT '20).

13. Hmoud, B., & Laszlo, V. (2019). *Will Artificial Intelligence Take Over Human Resources Recruitment and Selection?* Network Intelligence Studies, 7(13), 21–30.

14. van Esch, P., & Black, J. S. (2019). *Factors That Influence New Generation Candidates to Engage With and Complete Digital, AI-Enabled Recruiting*. Business Horizons, 62(6), 729–739.

## Database

15. Oracle Corporation. (2024). *MySQL 8.0 Reference Manual*. Retrieved from https://dev.mysql.com/doc/refman/8.0/en/

16. Laravel. (2024). *Eloquent ORM Documentation*. Retrieved from https://laravel.com/docs/11.x/eloquent

## Software Engineering

17. Fowler, M. (2018). *Refactoring: Improving the Design of Existing Code* (2nd ed.). Addison-Wesley Professional.

18. Martin, R. C. (2008). *Clean Code: A Handbook of Agile Software Craftsmanship*. Prentice Hall.

---

*End of Report*
