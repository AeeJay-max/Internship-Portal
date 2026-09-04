<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Information & Communication Technology',
                'code' => 'ICT',
                'description' => 'Manages digital infrastructure, portal systems, hardware maintenance, software development, and digital government services.',
                'contact_email' => 'ict@mosrac.gov.zw',
                'contact_phone' => '+263 242 700101',
                'capacity' => 10,
            ],
            [
                'name' => 'Human Resources',
                'code' => 'HR',
                'description' => 'Handles personnel recruitment, staff development, internship placement coordination, and organizational welfare.',
                'contact_email' => 'hr@mosrac.gov.zw',
                'contact_phone' => '+263 242 700102',
                'capacity' => 8,
            ],
            [
                'name' => 'Finance',
                'code' => 'FIN',
                'description' => 'Manages Ministry budgeting, financial reporting, accounting operations, and fiscal compliance.',
                'contact_email' => 'finance@mosrac.gov.zw',
                'contact_phone' => '+263 242 700103',
                'capacity' => 6,
            ],
            [
                'name' => 'Procurement',
                'code' => 'PROC',
                'description' => 'Oversees Ministry supply chain management, tendering processes, vendor relations, and asset acquisition.',
                'contact_email' => 'procurement@mosrac.gov.zw',
                'contact_phone' => '+263 242 700104',
                'capacity' => 5,
            ],
            [
                'name' => 'Administration',
                'code' => 'ADMIN',
                'description' => 'Directs general administrative operations, records management, logistics, and executive office support.',
                'contact_email' => 'admin@mosrac.gov.zw',
                'contact_phone' => '+263 242 700105',
                'capacity' => 8,
            ],
            [
                'name' => 'Legal Services',
                'code' => 'LEGAL',
                'description' => 'Provides legal counsel, policy drafting, contract drafting, and regulatory compliance for Ministry programs.',
                'contact_email' => 'legal@mosrac.gov.zw',
                'contact_phone' => '+263 242 700106',
                'capacity' => 4,
            ],
            [
                'name' => 'Sport Development',
                'code' => 'SPORT',
                'description' => 'Coordinates national sports initiatives, athlete development programs, sports federation oversight, and talent identification.',
                'contact_email' => 'sport@mosrac.gov.zw',
                'contact_phone' => '+263 242 700107',
                'capacity' => 12,
            ],
            [
                'name' => 'Recreation',
                'code' => 'REC',
                'description' => 'Promotes community wellness, recreational facilities access, physical activity programs, and youth outdoor initiatives.',
                'contact_email' => 'recreation@mosrac.gov.zw',
                'contact_phone' => '+263 242 700108',
                'capacity' => 8,
            ],
            [
                'name' => 'Arts',
                'code' => 'ARTS',
                'description' => 'Fosters visual and performing arts, national art galleries, creative industry development, and artist support programs.',
                'contact_email' => 'arts@mosrac.gov.zw',
                'contact_phone' => '+263 242 700109',
                'capacity' => 10,
            ],
            [
                'name' => 'Culture',
                'code' => 'CULT',
                'description' => 'Preserves national heritage, cultural monuments, traditional arts, language policies, and international cultural exchange.',
                'contact_email' => 'culture@mosrac.gov.zw',
                'contact_phone' => '+263 242 700110',
                'capacity' => 10,
            ],
            [
                'name' => 'Communications & Public Relations',
                'code' => 'CPR',
                'description' => 'Handles media relations, public statements, official announcements, social media, and Ministry publications.',
                'contact_email' => 'communications@mosrac.gov.zw',
                'contact_phone' => '+263 242 700111',
                'capacity' => 6,
            ],
            [
                'name' => 'Research, Monitoring & Evaluation',
                'code' => 'RME',
                'description' => 'Conducts policy research, program impact assessments, statistical analysis, and monitoring of national projects.',
                'contact_email' => 'research@mosrac.gov.zw',
                'contact_phone' => '+263 242 700112',
                'capacity' => 6,
            ],
            [
                'name' => 'Events Management',
                'code' => 'EVENTS',
                'description' => 'Organizes national sport tournaments, cultural festivals, official government galas, and international sports meets.',
                'contact_email' => 'events@mosrac.gov.zw',
                'contact_phone' => '+263 242 700113',
                'capacity' => 8,
            ],
            [
                'name' => 'Facilities Management',
                'code' => 'FAC',
                'description' => 'Maintains national stadiums, sports complexes, cultural centers, theaters, and administrative properties.',
                'contact_email' => 'facilities@mosrac.gov.zw',
                'contact_phone' => '+263 242 700114',
                'capacity' => 8,
            ],
        ];

        foreach ($departments as $dept) {
            Department::updateOrCreate(['code' => $dept['code']], $dept);
        }
    }
}
