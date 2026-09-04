<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\InternshipOpportunity;
use Illuminate\Database\Seeder;

class InternshipOpportunitySeeder extends Seeder
{
    public function run(): void
    {
        $ict = Department::where('code', 'ICT')->first();
        $sport = Department::where('code', 'SPORT')->first();
        $arts = Department::where('code', 'ARTS')->first();
        $hr = Department::where('code', 'HR')->first();

        if ($ict) {
            InternshipOpportunity::create([
                'department_id' => $ict->id,
                'title' => 'Software Systems & Network Administration Internship',
                'description' => 'Hands-on experience in managing government IT infrastructure, web application support, database administration, and user technical support.',
                'requirements' => 'Students or graduates in Computer Science, Information Technology, Software Engineering, or Information Systems.',
                'positions_count' => 5,
                'opening_date' => now()->subDays(10),
                'closing_date' => now()->addDays(60),
                'duration_months' => 6,
                'start_date' => now()->addDays(30),
                'status' => 'open',
            ]);
        }

        if ($sport) {
            InternshipOpportunity::create([
                'department_id' => $sport->id,
                'title' => 'Sports Administration & Talent Development Internship',
                'description' => 'Assist in organizing national youth sports trials, managing federation databases, and coordinating national sports events.',
                'requirements' => 'Background in Sports Management, Physical Education, Business Administration, or Social Sciences.',
                'positions_count' => 4,
                'opening_date' => now()->subDays(5),
                'closing_date' => now()->addDays(45),
                'duration_months' => 6,
                'start_date' => now()->addDays(20),
                'status' => 'open',
            ]);
        }

        if ($arts) {
            InternshipOpportunity::create([
                'department_id' => $arts->id,
                'title' => 'Cultural Heritage & Arts Exhibition Attachment',
                'description' => 'Work with national gallery curators, archive historical artifacts, and coordinate public cultural exhibitions.',
                'requirements' => 'Studies in Fine Arts, Cultural Studies, History, Archaeology, or Heritage Management.',
                'positions_count' => 3,
                'opening_date' => now()->subDays(15),
                'closing_date' => now()->addDays(30),
                'duration_months' => 6,
                'start_date' => now()->addDays(15),
                'status' => 'open',
            ]);
        }

        if ($hr) {
            InternshipOpportunity::create([
                'department_id' => $hr->id,
                'title' => 'Human Resources & Talent Placement Internship',
                'description' => 'Support internship onboarding, employee records digitalization, policy compliance, and staff training logistics.',
                'requirements' => 'Studies in Human Resource Management, Psychology, Business Management, or Public Administration.',
                'positions_count' => 3,
                'opening_date' => now()->subDays(2),
                'closing_date' => now()->addDays(40),
                'duration_months' => 6,
                'start_date' => now()->addDays(25),
                'status' => 'open',
            ]);
        }
    }
}
