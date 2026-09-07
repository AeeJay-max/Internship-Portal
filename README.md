# MoSRAC Internship Application Portal

A complete **Internship Application and Placement Management Portal** for the **Ministry of Sport, Recreation, Arts & Culture (MoSRAC)**.

The system manages the internship journey from **initial application and document submission through administrative review, approval, and placement**.

It supports both **general internship applications** and applications for specific advertised internship opportunities. Applicants can submit a general application even when there are currently no advertised vacancies.

The portal provides separate experiences for applicants and Ministry administrators, with role-based access, application tracking, document management, review workflows, and internship placement management.

---

## What It Does

### Applicant

Applicants can:

* Create an internship portal account.
* Submit a general internship application.
* Apply for an advertised internship opportunity when available.
* Select their preferred Ministry department.
* Provide personal information.
* Provide academic information.
* Specify internship preferences.
* Provide motivation and internship objectives.
* Upload required supporting documents.
* Review their application before submission.
* Track their application status.
* Receive application notifications.
* View placement information after approval and placement.

### Application Workflow

The system supports the following application lifecycle:

**Draft → Submitted → Under Review → Documents Required → Shortlisted → Interview Required → Approved → Placement Pending → Placed**

Applications can also be:

* Rejected
* Withdrawn

Each application receives a unique reference number in the format:

`MoSRAC-INT-2026-XXXXXX`

---

## General Internship Applications

A key feature of the portal is the ability to submit a **general internship application**.

Applicants do not have to wait for an advertised internship vacancy.

Even when there are **zero advertised internship opportunities**, an applicant can still:

1. Start an application.
2. Select their preferred Ministry department.
3. Select their preferred internship focus.
4. Provide their academic information.
5. Upload their supporting documents.
6. Submit the application.
7. Have the application reviewed by the Ministry.

General applications are stored without a specific opportunity:

`opportunity_id = NULL`

---

## Required Supporting Documents

Applicants are required to submit their supporting documents as **ONE PDF file**.

The single PDF must contain:

1. National ID
2. Academic Results
3. Current University Results showing all completed courses
4. Curriculum Vitae (CV)
5. University Internship Application/Recommendation Letter

The portal does **not** require applicants to upload each document separately.

### Upload Requirements

* **One PDF file per application**
* PDF format only
* Multiple file uploads are not permitted
* Other file formats are rejected
* Server-side validation is enforced
* Uploaded documents are protected from unauthorized access

Applicants are instructed to combine all required documents into a single PDF before uploading.

---

## Applicant Portal

Applicants have access to a personal internship portal where they can view:

* Application reference
* Application status
* Application progress
* Submission date
* Preferred department
* Internship preferences
* Supporting document status
* Application history
* Notifications
* Placement information

Applicants can only access their own applications and documents.

---

## Ministry Administration

The administrative portal provides tools for Ministry staff to manage the internship application process.

### Administration Dashboard

The dashboard provides an overview of applications, including:

* Total applications
* Submitted applications
* Applications under review
* Shortlisted applications
* Applications requiring documents
* Approved applications
* Placement pending
* Placed applicants
* Rejected applications

Where implemented, application information can also be viewed through charts and analytical summaries.

---

## Application Review

Administrators can open individual applications and review:

* Applicant personal information
* Academic information
* Internship preferences
* Motivation and objectives
* Supporting documents
* Application status
* Review history

Administrators can perform actions such as:

* Mark Under Review
* Shortlist
* Request Interview
* Request Documents
* Approve Application
* Reject Application
* Assign Placement

---

## Internship Opportunities

The system supports Ministry administrators creating and managing advertised internship opportunities.

Opportunities can contain information such as:

* Internship title
* Department
* Description
* Requirements
* Availability
* Application period
* Status

However, advertised opportunities are **optional**.

The portal continues to accept general internship applications when no vacancies are advertised.

---

## Internship Placement

After an application is approved, it can move to:

**Placement Pending**

A Placement Officer or authorized administrator can then assign the applicant to a placement.

Placement information can include:

* Department
* Supervisor
* Start date
* End date
* Station
* Placement code

Once assigned, the application moves to:

**Placed**

---

## User Roles

The system supports role-based access.

### Super Administrator

Responsible for overall system administration and management.

### Internship Administrator

Responsible for:

* Application review
* Internship opportunities
* Applicant management
* Application statuses
* Administrative processes

### Placement Officer

Responsible for:

* Placement management
* Assigning applicants
* Managing supervisors
* Placement details

### Applicant

Responsible for:

* Creating applications
* Providing information
* Uploading documents
* Submitting applications
* Tracking application progress

Each role only has access to functionality appropriate to its permissions.

---

## Security

The application implements role-based and server-side access controls.

Security considerations include:

* Authentication
* Role-based authorization
* Protected applicant information
* Applicant ownership checks
* Protected document access
* CSRF protection
* Server-side form validation
* Secure file validation
* PDF-only document uploads
* Database validation
* Protected administrative routes

Applicants cannot access other applicants' applications or documents.

---

## User Interface

The portal uses a modern government-oriented interface inspired by the visual identity of Zimbabwe.

The primary colour is:

**Green**

Supporting colours include:

* White
* Black
* Yellow
* Red

The design is intended to provide a consistent experience across:

* Public website
* Login and registration
* Application wizard
* Applicant dashboard
* Application review
* Administration dashboard
* Placement management

The interface is responsive and designed for:

* Desktop
* Laptop
* Tablet
* Mobile

---

## Public Website

The public portal contains dedicated pages for:

* Home
* About
* Internship Opportunities
* How to Apply
* Contact
* Login
* Apply Now

The homepage is intentionally minimal and directs applicants to the appropriate sections of the portal.

It does not depend on a news feed or long scrolling sections.

---

## Technology Stack

| Layer          | Technology                        |
| -------------- | --------------------------------- |
| Framework      | Laravel 12                        |
| Backend        | PHP                               |
| Database       | MySQL / SQLite for development    |
| Views          | Laravel Blade                     |
| Front-end      | Tailwind CSS                      |
| JavaScript     | Alpine.js                         |
| Build Tool     | Vite                              |
| Authentication | Laravel Authentication            |
| Authorization  | Role-Based Access Control         |
| File Storage   | Laravel Filesystem                |
| Localization   | Laravel Localization Architecture |

---

## Running It Locally

### Requirements

Make sure the following are installed:

* PHP 8.2+
* Composer
* Node.js 18+
* npm
* MySQL 8+ or SQLite
* Git

### 1. Clone the Repository

```bash
git clone <your-repository-url>

cd university_enrollment-master
```

### 2. Install Backend Dependencies

```bash
composer install
```

### 3. Install Frontend Dependencies

```bash
npm install
```

### 4. Configure Environment

Copy the example environment file:

```bash
cp .env.example .env
```

On Windows, you can also create `.env` manually from `.env.example`.

Generate the Laravel application key:

```bash
php artisan key:generate
```

### 5. Configure the Database

Configure the database settings in `.env`.

For MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mosrac_internship
DB_USERNAME=root
DB_PASSWORD=
```

Alternatively, SQLite can be used for local development if configured by the project.

### 6. Create the Database Schema

Run:

```bash
php artisan migrate --seed
```

If a completely fresh development database is required:

```bash
php artisan migrate:fresh --seed
```

### 7. Link Storage

Run:

```bash
php artisan storage:link
```

This allows uploaded application documents to be handled through Laravel's configured storage system.

### 8. Build Frontend Assets

For a production-style build:

```bash
npm run build
```

For development with hot reloading:

```bash
npm run dev
```

### 9. Start Laravel

```bash
php artisan serve
```

Open:

```text
http://127.0.0.1:8000
```

---

## Demo Accounts

The database seeder can create development/demo accounts for testing.

| Role                     | Email                      | Password   |
| ------------------------ | -------------------------- | ---------- |
| Super Administrator      | `superadmin@mosrac.gov.zw` | `password` |
| Internship Administrator | `admin@mosrac.gov.zw`      | `password` |
| Placement Officer        | `placement@mosrac.gov.zw`  | `password` |
| Applicant                | `applicant@mosrac.gov.zw`  | `password` |

These accounts are intended for **local development and testing only**.

Do not use these default passwords in a production deployment.

---

## Application Architecture

The system is structured around the following major areas:

```text
Public Portal
      │
      ├── Home
      ├── About
      ├── Internship Opportunities
      ├── How to Apply
      ├── Contact
      └── Authentication
              │
              ▼
        Applicant Portal
              │
              ├── Application
              ├── Documents
              ├── Preferences
              ├── Status Tracking
              └── Placement
              │
              ▼
       Ministry Administration
              │
              ├── Applications
              ├── Reviews
              ├── Opportunities
              ├── Departments
              ├── Users
              └── Placements
```

---

## Application Lifecycle

The complete internship workflow is:

```text
Applicant
   │
   ▼
Create Account
   │
   ▼
Start Application
   │
   ├── General Application
   │
   └── Advertised Opportunity
   │
   ▼
Complete Application
   │
   ▼
Upload One Combined PDF
   │
   ▼
Review Application
   │
   ▼
Submit
   │
   ▼
Under Review
   │
   ├── Documents Required
   ├── Interview Required
   └── Shortlisted
   │
   ▼
Approved
   │
   ▼
Placement Pending
   │
   ▼
Placement Assigned
   │
   ▼
Placed
```

---

## Project Status

The project is currently designed as a **local Laravel internship application and placement management system**.

The system provides the core workflow for:

* Internship applications
* General applications
* Advertised opportunities
* Document submission
* Application review
* Application status tracking
* Applicant management
* Role-based administration
* Internship placement

Future development can include:

* Production deployment
* Automated testing
* Email/SMS notifications
* Advanced reporting
* Placement monitoring
* Additional Ministry workflows
* Enhanced document verification

---

## Important Information

This project is intended to provide an internship application and management platform for the Ministry of Sport, Recreation, Arts & Culture.

Any Ministry contact information, departments, programmes, statistics, addresses, telephone numbers, email addresses, logos or other official information displayed by the system should be verified before production use.

Development/demo data should not be treated as official Ministry data.

---

## License

Copyright © 2026.

All rights reserved unless otherwise specified by the repository owner.

This repository is intended for development, demonstration and evaluation purposes.

The code should not be redistributed or used commercially without appropriate authorization.
