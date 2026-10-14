<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        // Migrate legacy department codes if present to maintain relational integrity
        $legacyMappings = [
            'REC'   => 'REC_OLD', // legacy Recreation
            'SPORT' => 'SR',
            'ARTS'  => 'ACPD',
            'FIN'   => 'FA',
            'PROC'  => 'PMU',
            'CPR'   => 'CA',
            'RME'   => 'ME',
        ];

        foreach ($legacyMappings as $oldCode => $newCode) {
            $legacyDept = Department::where('code', $oldCode)->first();
            if ($legacyDept) {
                $legacyDept->update(['code' => $newCode]);
            }
        }

        $departments = [
            [
                'name' => 'Sports And Recreation',
                'code' => 'SR',
                'description' => 'Oversees national sports initiatives, recreation programs, athlete development, and community wellness.',
                'contact_email' => 'sports@mosrac.gov.zw',
                'contact_phone' => '+263 242 700101',
                'capacity' => 12,
            ],
            [
                'name' => 'Promotion And Development',
                'code' => 'PD',
                'description' => 'Leads youth, sports, and cultural promotion initiatives, community outreach, and institutional development programs.',
                'contact_email' => 'promotion@mosrac.gov.zw',
                'contact_phone' => '+263 242 700102',
                'capacity' => 8,
            ],
            [
                'name' => 'Human Resources',
                'code' => 'HR',
                'description' => 'Handles personnel recruitment, staff development, internship placement coordination, and organizational welfare.',
                'contact_email' => 'hr@mosrac.gov.zw',
                'contact_phone' => '+263 242 700103',
                'capacity' => 8,
            ],
            [
                'name' => 'Communication and Advocacy',
                'code' => 'CA',
                'description' => 'Directs media relations, public advocacy, official communications, stakeholder engagement, and Ministry publications.',
                'contact_email' => 'communication@mosrac.gov.zw',
                'contact_phone' => '+263 242 700104',
                'capacity' => 6,
            ],
            [
                'name' => 'Information Communication Technology',
                'code' => 'ICT',
                'description' => 'Manages digital infrastructure, portal systems, hardware maintenance, software development, and digital government services.',
                'contact_email' => 'ict@mosrac.gov.zw',
                'contact_phone' => '+263 242 700105',
                'capacity' => 10,
            ],
            [
                'name' => 'Strategic Policy Planning',
                'code' => 'SPP',
                'description' => 'Formulates strategic plans, coordinates national policy frameworks, and drives long-term Ministry development goals.',
                'contact_email' => 'planning@mosrac.gov.zw',
                'contact_phone' => '+263 242 700106',
                'capacity' => 6,
            ],
            [
                'name' => 'Arts and Culture Promotion development',
                'code' => 'ACPD',
                'description' => 'Promotes visual and performing arts, preserves cultural heritage, supports creative industries, and coordinates cultural festivals.',
                'contact_email' => 'artsculture@mosrac.gov.zw',
                'contact_phone' => '+263 242 700107',
                'capacity' => 10,
            ],
            [
                'name' => 'Monitoring and Evaluation',
                'code' => 'ME',
                'description' => 'Assesses Ministry program performance, conducts impact evaluations, and delivers data-driven reporting.',
                'contact_email' => 'monitoring@mosrac.gov.zw',
                'contact_phone' => '+263 242 700108',
                'capacity' => 6,
            ],
            [
                'name' => 'Finance and Administration',
                'code' => 'FA',
                'description' => 'Directs budgeting, financial management, accounting, general administrative services, and facility operations.',
                'contact_email' => 'finance@mosrac.gov.zw',
                'contact_phone' => '+263 242 700109',
                'capacity' => 8,
            ],
            [
                'name' => 'Internal Audit',
                'code' => 'IA',
                'description' => 'Conducts independent financial and operational audits, risk assessments, and ensures governance compliance.',
                'contact_email' => 'audit@mosrac.gov.zw',
                'contact_phone' => '+263 242 700110',
                'capacity' => 5,
            ],
            [
                'name' => 'Procurement Management Unit',
                'code' => 'PMU',
                'description' => 'Oversees public procurement, supply chain integrity, tendering processes, and vendor compliance.',
                'contact_email' => 'procurement@mosrac.gov.zw',
                'contact_phone' => '+263 242 700111',
                'capacity' => 6,
            ],
            [
                'name' => 'Gender Mainstreaming and Inclusivity',
                'code' => 'GMI',
                'description' => 'Promotes gender equality, disability inclusion, and mainstreaming in all Ministry policies and opportunities.',
                'contact_email' => 'gender@mosrac.gov.zw',
                'contact_phone' => '+263 242 700112',
                'capacity' => 6,
            ],
            [
                'name' => 'Records',
                'code' => 'REC',
                'description' => 'Manages official registries, document archives, records management systems, and knowledge preservation.',
                'contact_email' => 'records@mosrac.gov.zw',
                'contact_phone' => '+263 242 700113',
                'capacity' => 6,
            ],
        ];

        // Deactivate old departments that are not in the new list of 13
        $activeCodes = array_column($departments, 'code');
        Department::whereNotIn('code', $activeCodes)->update(['is_active' => false]);

        // Insert or update the 13 official departments
        foreach ($departments as $dept) {
            Department::updateOrCreate(
                ['code' => $dept['code']],
                array_merge($dept, ['is_active' => true])
            );
        }
    }
}
