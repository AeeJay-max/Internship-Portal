<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'title'        => 'Ministry of Sport, Recreation, Arts & Culture Opens National Internship Portal',
                'category'     => 'general',
                'published_at' => Carbon::now()->subDays(5),
                'excerpt'      => 'The Ministry of Sport, Recreation, Arts & Culture has launched its official national internship portal for tertiary students and recent graduates.',
                'body'         => "The Ministry of Sport, Recreation, Arts & Culture is pleased to announce that the official National Internship Portal is now active and open for applications.\n\nStudents and graduates from recognized universities, polytechnics, and tertiary institutions across Zimbabwe can now submit general internship applications or apply directly for advertised department opportunities.\n\nApplicants can select their preferred departments including ICT, Sport Development, Recreation, Arts, Culture, HR, Legal Services, and Communications.\n\nTo apply, create an applicant account and complete the online internship application wizard.",
            ],
            [
                'title'        => 'Ministry Expands Youth Sport Development and Cultural Exchange Programs',
                'category'     => 'general',
                'published_at' => Carbon::now()->subDays(12),
                'excerpt'      => 'New initiatives in youth athletic training and national cultural preservation will provide expanded placement roles for interns in 2026.',
                'body'         => "The Ministry of Sport, Recreation, Arts & Culture has announced a multi-department initiative expanding youth sport development centers and heritage preservation projects.\n\nInterns placed in the Department of Sport Development and Department of Culture will gain hands-on administrative and operational experience participating in national talent trials and national gallery archiving projects.",
            ],
            [
                'title'        => 'Annual Arts & Culture Festival 2026 Preparations Underway',
                'category'     => 'events',
                'published_at' => Carbon::now()->subDays(18),
                'excerpt'      => 'The Ministry\'s Department of Arts and Events Management will host the National Arts Festival featuring artists from all ten provinces.',
                'body'         => "Preparations for the 2026 National Arts & Culture Festival have officially commenced. Interns attached to Events Management and Arts departments will assist with event logistics, artist accreditation, and exhibition staging.",
            ],
        ];

        $adminId = DB::table('users')->whereIn('role', ['admin', 'super_admin'])->value('id') ?? 1;

        foreach ($articles as $article) {
            $slug = Str::slug($article['title']);
            if (DB::table('news')->where('slug', $slug)->exists()) {
                continue;
            }

            DB::table('news')->insert([
                'title'        => $article['title'],
                'slug'         => $slug,
                'excerpt'      => $article['excerpt'],
                'body'         => $article['body'],
                'category'     => $article['category'],
                'is_published' => true,
                'published_at' => $article['published_at'],
                'created_by'   => $adminId,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}
