<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [

            // ── Bachelor's ──────────────────────────────────────────────
            [
                'name'         => 'Computer Science & Engineering',
                'faculty'      => 'Faculty of Informatics & Applied Mathematics',
                'degree_level' => 'bachelor',
                'description'  => 'A rigorous 4-year program covering algorithms, data structures, software engineering, operating systems, computer networks, and artificial intelligence. Students gain hands-on experience through laboratory work, team projects, and a final-year capstone with an industry partner. Graduates are highly sought by leading technology companies in Armenia and internationally.',
                'is_active'    => true,
                'is_featured'  => true,
                'sort_order'   => 1,
            ],
            [
                'name'         => 'Electrical & Electronic Engineering',
                'faculty'      => 'Faculty of Power Engineering',
                'degree_level' => 'bachelor',
                'description'  => 'Covers the theory and application of electricity, electronics, and electromagnetism. Core topics include circuit analysis, digital electronics, signal processing, power systems, and embedded systems design. Students complete practical lab projects and an industrial placement in their third year.',
                'is_active'    => true,
                'is_featured'  => true,
                'sort_order'   => 2,
            ],
            [
                'name'         => 'Mechanical Engineering',
                'faculty'      => 'Faculty of Engineering',
                'degree_level' => 'bachelor',
                'description'  => 'Combines thermodynamics, fluid mechanics, material science, and machine design with modern CAD/CAM tools. Students learn to design, analyse, and manufacture mechanical systems. The program includes a strong practical component with access to MOSRAC\'s state-of-the-art engineering workshops.',
                'is_active'    => true,
                'is_featured'  => true,
                'sort_order'   => 3,
            ],
            [
                'name'         => 'Architecture & Urban Design',
                'faculty'      => 'Faculty of Architecture & Design',
                'degree_level' => 'bachelor',
                'description'  => 'Blends creative design with engineering principles to prepare architects for 21st-century challenges. Topics include architectural history, structural systems, environmental design, urban planning, and digital modelling. Students develop a professional portfolio through studio projects and international design competitions.',
                'is_active'    => true,
                'is_featured'  => false,
                'sort_order'   => 4,
            ],
            [
                'name'         => 'Information Technology',
                'faculty'      => 'Faculty of Informatics & Applied Mathematics',
                'degree_level' => 'bachelor',
                'description'  => 'Focuses on practical IT skills including web development, database administration, network infrastructure, cloud computing, and IT project management. Designed in close collaboration with industry partners, the curriculum emphasises employability and real-world problem solving from day one.',
                'is_active'    => true,
                'is_featured'  => true,
                'sort_order'   => 5,
            ],
            [
                'name'         => 'Chemical & Materials Engineering',
                'faculty'      => 'Faculty of Chemical Engineering',
                'degree_level' => 'bachelor',
                'description'  => 'Explores the design and optimisation of chemical processes, materials synthesis, and industrial chemistry. Students work in fully equipped analytical laboratories and gain experience with modern simulation software. Career paths include the pharmaceutical, energy, and manufacturing sectors.',
                'is_active'    => true,
                'is_featured'  => false,
                'sort_order'   => 6,
            ],

            // ── Master's ─────────────────────────────────────────────────
            [
                'name'         => 'Artificial Intelligence & Data Science',
                'faculty'      => 'Faculty of Informatics & Applied Mathematics',
                'degree_level' => 'master',
                'description'  => 'An advanced 2-year program at the cutting edge of machine learning, deep learning, natural language processing, computer vision, and big data analytics. Students work on real datasets provided by industry partners and complete a research thesis. The program is taught in English and attracts students from across the region.',
                'is_active'    => true,
                'is_featured'  => true,
                'sort_order'   => 1,
            ],
            [
                'name'         => 'Cybersecurity',
                'faculty'      => 'Faculty of Informatics & Applied Mathematics',
                'degree_level' => 'master',
                'description'  => 'Prepares specialists in network security, cryptography, ethical hacking, digital forensics, and security policy. Developed with Picsart, Unibank, and the National Cybersecurity Center of Armenia to reflect current industry requirements. Includes a 6-month industry internship and is taught in English.',
                'is_active'    => true,
                'is_featured'  => true,
                'sort_order'   => 2,
            ],
            [
                'name'         => 'Engineering Management',
                'faculty'      => 'Faculty of Engineering',
                'degree_level' => 'master',
                'description'  => 'Bridges technical engineering expertise with modern management skills. Core modules include project management, strategic planning, operations research, innovation management, and leadership. Ideal for engineers seeking to move into senior technical or management roles. Includes international case studies and a practical industry project.',
                'is_active'    => true,
                'is_featured'  => true,
                'sort_order'   => 3,
            ],
            [
                'name'         => 'Renewable Energy Systems',
                'faculty'      => 'Faculty of Power Engineering',
                'degree_level' => 'master',
                'description'  => 'Addresses the growing demand for clean energy engineers. Covers solar, wind, hydro, and biomass energy systems, smart grid technology, energy storage, and policy frameworks. Students conduct applied research in MOSRAC\'s energy laboratory and collaborate with the Armenian Energy Regulatory Commission.',
                'is_active'    => true,
                'is_featured'  => false,
                'sort_order'   => 4,
            ],

            // ── PhD ──────────────────────────────────────────────────────
            [
                'name'         => 'PhD in Computer Science',
                'faculty'      => 'Faculty of Informatics & Applied Mathematics',
                'degree_level' => 'phd',
                'description'  => 'A 3–5 year doctoral program for researchers pursuing original contributions to computer science. Research areas include artificial intelligence, algorithms and complexity, distributed systems, computer vision, and human-computer interaction. PhD candidates are supervised by internationally recognised faculty and are expected to publish in leading peer-reviewed journals.',
                'is_active'    => true,
                'is_featured'  => true,
                'sort_order'   => 1,
            ],
            [
                'name'         => 'PhD in Engineering Sciences',
                'faculty'      => 'Faculty of Engineering',
                'degree_level' => 'phd',
                'description'  => 'Supports advanced research across all engineering disciplines including mechanical, civil, electrical, and industrial engineering. Candidates work within one of MOSRAC\'s dedicated research centres and have access to state-of-the-art facilities. The program includes coursework in research methods, academic writing, and technology transfer.',
                'is_active'    => true,
                'is_featured'  => false,
                'sort_order'   => 2,
            ],
        ];

        foreach ($programs as $program) {
            // Skip if already exists by name
            if (DB::table('programs')->where('name', $program['name'])->exists()) {
                continue;
            }

            DB::table('programs')->insert(array_merge($program, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('✓ Programs seeded — ' . count($programs) . ' programs inserted.');
    }
}
