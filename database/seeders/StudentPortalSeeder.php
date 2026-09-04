<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Lecturer;
use App\Models\ScheduleSlot;
use App\Models\Student;
use App\Models\StudentSubjectEnrollment;
use App\Models\Grade;

class StudentPortalSeeder extends Seeder
{
    /*
    |--------------------------------------------------------------------------
    | MOSRAC Building & Room System
    |--------------------------------------------------------------------------
    | Room code format: [Building][Floor][Room]
    | Examples:
    |   5505  = Building 5, Floor 5,  Room 05
    |   51008 = Building 5, Floor 10, Room 08
    |   5013  = Building 5, Floor 0,  Room 13  (ground floor)
    |   9908  = Building 9, Floor 9,  Room 08
    |   21    = Building 2, Floor 1   (sports)
    |
    | Buildings:
    |   Building 5 — IT & CS (12 floors, ~20 rooms/floor)
    |   Building 9 — Humanities (Armenian, History, Russian, Languages)
    |   Building 1 — Life Safety, Architecture, General
    |   Building 2 — Sports (basketball court, gym)
    |
    |--------------------------------------------------------------------------
    | Grading System (Bachelor)
    |--------------------------------------------------------------------------
    | Midterm 1: out of 20
    | Midterm 2: out of 20
    | Final Exam: out of 60
    | Total: out of 100
    |
    | For Master/PhD we store combined midterm (40) + final (60) = 100
    |--------------------------------------------------------------------------
    */

    public function run(): void
    {
        $this->command->info('Seeding Student Portal data (MOSRAC system)...');
        $this->command->info('');

        // ── 1. SEMESTER ──────────────────────────────────────────────
        Semester::query()->update(['is_current' => false]);

        $semester = Semester::updateOrCreate(
            ['name' => 'Spring 2026'],
            [
                'academic_year' => '2025-2026',
                'starts_at'     => '2026-02-01',
                'ends_at'       => '2026-06-30',
                'is_current'    => true,
            ]
        );

        $this->command->info('  ✓ Semester: Spring 2026 (current)');

        // ── 2. LECTURERS ─────────────────────────────────────────────
        // Real MOSRAC-style Armenian lecturer names
        $lecturers = [
            // Building 5 — IT/CS lecturers
            'simonyan'      => Lecturer::firstOrCreate(['email' => 'a.simonyan@MOSRAC.am'],      ['name' => 'Armen Simonyan',       'title' => 'Assoc. Prof.', 'faculty' => 'Faculty of Informatics & Applied Mathematics']),
            'sharkhatunyan' => Lecturer::firstOrCreate(['email' => 'h.sharkhatunyan@MOSRAC.am'], ['name' => 'Hayk Sharkhatunyan',   'title' => 'Dr.',          'faculty' => 'Faculty of Informatics & Applied Mathematics']),
            'danielyan'     => Lecturer::firstOrCreate(['email' => 'n.danielyan@MOSRAC.am'],     ['name' => 'Nadya Danielyan',      'title' => 'Dr.',          'faculty' => 'Faculty of Informatics & Applied Mathematics']),
            'petrosyan'     => Lecturer::firstOrCreate(['email' => 'a.petrosyan@MOSRAC.am'],     ['name' => 'Armen Petrosyan',      'title' => 'Prof.',        'faculty' => 'Faculty of Informatics & Applied Mathematics']),
            'harutyunyan'   => Lecturer::firstOrCreate(['email' => 'n.harutyunyan@MOSRAC.am'],   ['name' => 'Narine Harutyunyan',   'title' => 'Dr.',          'faculty' => 'Faculty of Informatics & Applied Mathematics']),
            'grigoryan'     => Lecturer::firstOrCreate(['email' => 'v.grigoryan@MOSRAC.am'],     ['name' => 'Vardan Grigoryan',     'title' => 'Assoc. Prof.', 'faculty' => 'Faculty of Informatics & Applied Mathematics']),
            'mkrtchyan'     => Lecturer::firstOrCreate(['email' => 's.mkrtchyan@MOSRAC.am'],     ['name' => 'Sona Mkrtchyan',       'title' => 'Dr.',          'faculty' => 'Faculty of Informatics & Applied Mathematics']),
            // Building 9 — Humanities
            'gevorgyan'     => Lecturer::firstOrCreate(['email' => 'l.gevorgyan@MOSRAC.am'],     ['name' => 'Lilit Gevorgyan',      'title' => 'Dr.',          'faculty' => 'Faculty of Social Sciences & Humanities']),
            'sahakyan'      => Lecturer::firstOrCreate(['email' => 'a.sahakyan@MOSRAC.am'],      ['name' => 'Anna Sahakyan',        'title' => 'Dr.',          'faculty' => 'Faculty of Social Sciences & Humanities']),
            // Building 1 — Engineering & General
            'abrahamyan'    => Lecturer::firstOrCreate(['email' => 't.abrahamyan@MOSRAC.am'],    ['name' => 'Tigran Abrahamyan',    'title' => 'Prof.',        'faculty' => 'Faculty of Engineering']),
            'sargsyan'      => Lecturer::firstOrCreate(['email' => 'v.sargsyan@MOSRAC.am'],      ['name' => 'Vahagn Sargsyan',      'title' => 'Assoc. Prof.', 'faculty' => 'Faculty of Engineering']),
            // Building 2 — Sports
            'hovhannisyan'  => Lecturer::firstOrCreate(['email' => 'g.hovhannisyan@MOSRAC.am'],  ['name' => 'Gagik Hovhannisyan',   'title' => 'Coach',        'faculty' => 'Faculty of Physical Education']),
        ];

        $this->command->info('  ✓ ' . count($lecturers) . ' lecturers ready');

        // ── 3. SUBJECT TEMPLATES PER PROGRAM ─────────────────────────
        // Room format: [Building][Floor padded][Room padded]
        // 5505  = B5, Floor 5, Room 05
        // 51008 = B5, Floor 10, Room 08
        // 5013  = B5, Floor 0,  Room 13
        // 9908  = B9, Floor 9,  Room 08
        // 1205  = B1, Floor 2,  Room 05
        // BC2   = Basketball Court 2 (Building 2)

        $subjectsByProgram = [

            // ── Computer Science & Engineering (BSc) ─────────────────
            'Computer Science & Engineering' => [
                ['code' => 'CSE301', 'name' => 'Data Structures & Algorithms', 'credits' => 4, 'lecturer' => 'simonyan',      'day' => 'Monday',    'time_start' => '09:30', 'time_end' => '10:50', 'building' => 'Building 5', 'room' => '5505',  'type' => 'lecture'],
                ['code' => 'CSE302', 'name' => 'Computer Networking',          'credits' => 3, 'lecturer' => 'sharkhatunyan', 'day' => 'Tuesday',   'time_start' => '11:00', 'time_end' => '12:20', 'building' => 'Building 5', 'room' => '51008', 'type' => 'lecture'],
                ['code' => 'CSE303', 'name' => 'Mobile Technologies',          'credits' => 3, 'lecturer' => 'danielyan',     'day' => 'Wednesday', 'time_start' => '14:20', 'time_end' => '15:40', 'building' => 'Building 5', 'room' => '5013',  'type' => 'lecture'],
                ['code' => 'CSE304', 'name' => 'Armenian History',             'credits' => 2, 'lecturer' => 'gevorgyan',     'day' => 'Thursday',  'time_start' => '09:30', 'time_end' => '10:50', 'building' => 'Building 9', 'room' => '9908',  'type' => 'lecture'],
                ['code' => 'CSE305', 'name' => 'Physical Education',           'credits' => 1, 'lecturer' => 'hovhannisyan',  'day' => 'Friday',    'time_start' => '11:00', 'time_end' => '12:20', 'building' => 'Building 2', 'room' => 'BC2',   'type' => 'seminar'],
            ],

            // ── Computer Science (PhD) ────────────────────────────────
            'Computer Science' => [
                ['code' => 'CS601', 'name' => 'Advanced Machine Learning',     'credits' => 4, 'lecturer' => 'petrosyan',     'day' => 'Monday',    'time_start' => '11:00', 'time_end' => '12:20', 'building' => 'Building 5', 'room' => '5807',  'type' => 'lecture'],
                ['code' => 'CS602', 'name' => 'Distributed Computing',         'credits' => 4, 'lecturer' => 'harutyunyan',   'day' => 'Tuesday',   'time_start' => '14:20', 'time_end' => '15:40', 'building' => 'Building 5', 'room' => '51205', 'type' => 'lecture'],
                ['code' => 'CS603', 'name' => 'Research Methodology',          'credits' => 3, 'lecturer' => 'grigoryan',     'day' => 'Wednesday', 'time_start' => '09:30', 'time_end' => '10:50', 'building' => 'Building 5', 'room' => '5605',  'type' => 'seminar'],
                ['code' => 'CS604', 'name' => 'Deep Learning Systems',         'credits' => 3, 'lecturer' => 'petrosyan',     'day' => 'Thursday',  'time_start' => '11:00', 'time_end' => '12:20', 'building' => 'Building 5', 'room' => '5807',  'type' => 'lab'],
                ['code' => 'CS605', 'name' => 'Scientific Writing & Ethics',   'credits' => 2, 'lecturer' => 'gevorgyan',     'day' => 'Friday',    'time_start' => '14:20', 'time_end' => '15:40', 'building' => 'Building 9', 'room' => '9504',  'type' => 'seminar'],
            ],

            // ── Information Technology (MSc) ──────────────────────────
            'Information Technology' => [
                ['code' => 'IT501', 'name' => 'Cloud Computing Architecture',  'credits' => 3, 'lecturer' => 'harutyunyan',   'day' => 'Monday',    'time_start' => '11:00', 'time_end' => '12:20', 'building' => 'Building 5', 'room' => '51106', 'type' => 'lecture'],
                ['code' => 'IT502', 'name' => 'Cybersecurity & Cryptography', 'credits' => 3, 'lecturer' => 'sharkhatunyan', 'day' => 'Tuesday',   'time_start' => '09:30', 'time_end' => '10:50', 'building' => 'Building 5', 'room' => '51008', 'type' => 'lecture'],
                ['code' => 'IT503', 'name' => 'Advanced Database Systems',     'credits' => 3, 'lecturer' => 'mkrtchyan',     'day' => 'Wednesday', 'time_start' => '11:00', 'time_end' => '12:20', 'building' => 'Building 5', 'room' => '5706',  'type' => 'lab'],
                ['code' => 'IT504', 'name' => 'IT Project Management',         'credits' => 2, 'lecturer' => 'abrahamyan',    'day' => 'Thursday',  'time_start' => '14:20', 'time_end' => '15:40', 'building' => 'Building 1', 'room' => '1205',  'type' => 'lecture'],
                ['code' => 'IT505', 'name' => 'Network Security Practicum',    'credits' => 3, 'lecturer' => 'sharkhatunyan', 'day' => 'Friday',    'time_start' => '09:30', 'time_end' => '10:50', 'building' => 'Building 5', 'room' => '51008', 'type' => 'lab'],
            ],

            // ── Engineering Management (MSc) ──────────────────────────
            'Engineering Management' => [
                ['code' => 'EM501', 'name' => 'Operations Management',         'credits' => 3, 'lecturer' => 'abrahamyan',    'day' => 'Monday',    'time_start' => '09:30', 'time_end' => '10:50', 'building' => 'Building 1', 'room' => '1304',  'type' => 'lecture'],
                ['code' => 'EM502', 'name' => 'Strategic Management',          'credits' => 3, 'lecturer' => 'sargsyan',      'day' => 'Tuesday',   'time_start' => '11:00', 'time_end' => '12:20', 'building' => 'Building 1', 'room' => '1306',  'type' => 'lecture'],
                ['code' => 'EM503', 'name' => 'Engineering Economics',         'credits' => 3, 'lecturer' => 'abrahamyan',    'day' => 'Wednesday', 'time_start' => '09:30', 'time_end' => '10:50', 'building' => 'Building 1', 'room' => '1304',  'type' => 'lecture'],
                ['code' => 'EM504', 'name' => 'Quality Management Systems',    'credits' => 3, 'lecturer' => 'sargsyan',      'day' => 'Thursday',  'time_start' => '14:20', 'time_end' => '15:40', 'building' => 'Building 1', 'room' => '1208',  'type' => 'seminar'],
                ['code' => 'EM505', 'name' => 'Research & Innovation Mgmt',    'credits' => 2, 'lecturer' => 'gevorgyan',     'day' => 'Friday',    'time_start' => '14:20', 'time_end' => '15:40', 'building' => 'Building 9', 'room' => '9406',  'type' => 'seminar'],
            ],

            // ── Civil Engineering (BSc/MSc) ───────────────────────────
            'Civil Engineering' => [
                ['code' => 'CE301', 'name' => 'Structural Analysis',           'credits' => 4, 'lecturer' => 'sargsyan',      'day' => 'Monday',    'time_start' => '09:30', 'time_end' => '10:50', 'building' => 'Building 1', 'room' => '1105',  'type' => 'lecture'],
                ['code' => 'CE302', 'name' => 'Construction Materials',        'credits' => 3, 'lecturer' => 'abrahamyan',    'day' => 'Tuesday',   'time_start' => '11:00', 'time_end' => '12:20', 'building' => 'Building 1', 'room' => '1204',  'type' => 'lab'],
                ['code' => 'CE303', 'name' => 'Geotechnical Engineering',      'credits' => 3, 'lecturer' => 'sargsyan',      'day' => 'Wednesday', 'time_start' => '09:30', 'time_end' => '10:50', 'building' => 'Building 1', 'room' => '1105',  'type' => 'lecture'],
                ['code' => 'CE304', 'name' => 'Armenian History',              'credits' => 2, 'lecturer' => 'sahakyan',      'day' => 'Thursday',  'time_start' => '11:00', 'time_end' => '12:20', 'building' => 'Building 9', 'room' => '9302',  'type' => 'lecture'],
                ['code' => 'CE305', 'name' => 'Physical Education',            'credits' => 1, 'lecturer' => 'hovhannisyan',  'day' => 'Friday',    'time_start' => '11:00', 'time_end' => '12:20', 'building' => 'Building 2', 'room' => 'BC2',   'type' => 'seminar'],
            ],
        ];

        // ── 4. GRADE PROFILES ────────────────────────────────────────
        // Bachelor: [midterm1 (max 20), midterm2 (max 20), final (max 60)] = 100 total
        // Master/PhD: [midterm (max 40), final (max 60)] — combined format
        // Stored as: midterm = midterm1+midterm2 (or single), final = final exam
        // participation = 0 (not used in MOSRAC system shown here)

        $gradeProfiles = [
            //                           M1    M2    Final
            'MOSRAC-2026-0001' => [[16,14,48], [15,13,44], [17,16,50], [14,15,46], [16,14,49]], // ~83 avg → B
            'MOSRAC-2026-0002' => [[18,17,52], [17,16,50], [19,17,54], [17,17,51], [18,17,53]], // ~90 avg → A
            'MOSRAC-2026-0003' => [[13,12,42], [12,11,40], [14,13,44], [12,12,41], [13,12,43]], // ~70 avg → C
            'MOSRAC-2026-0004' => [[19,18,55], [18,17,53], [20,18,56], [18,18,54], [19,18,55]], // ~95 avg → A
            'MOSRAC-2026-0005' => [[16,15,50], [15,14,47], [17,15,51], [15,15,49], [16,15,50]], // ~83 avg → B
            'MOSRAC-2026-0006' => [[19,18,56], [18,17,54], [20,18,57], [18,18,55], [19,19,57]], // ~95 avg → A
            'MOSRAC-2026-0007' => [[15,14,47], [14,13,45], [16,14,48], [14,14,46], [15,14,48]], // ~80 avg → B
            'MOSRAC-2026-0008' => [[20,19,58], [19,19,57], [20,20,59], [19,19,58], [20,19,59]], // ~98 avg → A
            'MOSRAC-2026-0009' => [[14,13,44], [13,12,42], [15,13,45], [13,13,43], [14,13,44]], // ~73 avg → C+
            'MOSRAC-2026-0010' => [[17,16,51], [16,15,50], [18,16,52], [16,16,51], [17,16,52]], // ~86 avg → B+
            'MOSRAC-2026-0011' => [[18,17,54], [17,16,52], [19,17,55], [17,17,53], [18,17,54]], // ~91 avg → A
        ];

        // ── 5. PROCESS EACH STUDENT ──────────────────────────────────
        $students = Student::with(['application.program'])->get();

        foreach ($students as $student) {
            $programName = $student->application?->program?->name;

            if (!$programName || !isset($subjectsByProgram[$programName])) {
                $this->command->warn('  Skipping ' . $student->student_number . ' — no template for: ' . ($programName ?? 'null'));
                continue;
            }

            $this->command->info('  ' . $student->student_number . ' — ' . $programName);

            $program     = $student->application->program;
            $subjectList = $subjectsByProgram[$programName];
            $grades      = $gradeProfiles[$student->student_number] ?? null;

            foreach ($subjectList as $index => $sd) {

                // Create subject
                $subject = Subject::firstOrCreate(
                    ['code' => $sd['code'], 'semester_id' => $semester->id],
                    [
                        'name'        => $sd['name'],
                        'credits'     => $sd['credits'],
                        'program_id'  => $program->id,
                        'semester_id' => $semester->id,
                    ]
                );

                // Create schedule slot
                ScheduleSlot::firstOrCreate(
                    ['subject_id' => $subject->id, 'day' => $sd['day']],
                    [
                        'lecturer_id' => $lecturers[$sd['lecturer']]->id,
                        'time_start'  => $sd['time_start'],
                        'time_end'    => $sd['time_end'],
                        'building'    => $sd['building'],
                        'room'        => $sd['room'],
                        'type'        => $sd['type'],
                    ]
                );

                // Enroll student
                $enrollment = StudentSubjectEnrollment::firstOrCreate(
                    [
                        'student_id'  => $student->id,
                        'subject_id'  => $subject->id,
                        'semester_id' => $semester->id,
                    ],
                    ['status' => 'enrolled']
                );

                // Add grades
                if ($grades && isset($grades[$index])) {
                    [$midterm1, $midterm2, $final] = $grades[$index];
                    $midterm = $midterm1 + $midterm2; // combined midterm (max 40)
                    $total   = $midterm + $final;      // total out of 100

                    // Calculate letter grade and GPA points
                    $calculated = Grade::calculate($midterm, $final, 0);

                    Grade::updateOrCreate(
                        ['enrollment_id' => $enrollment->id],
                        [
                            'midterm'       => $midterm,   // combined midterms (max 40)
                            'final'         => $final,     // final exam (max 60)
                            'participation' => 0,
                            'total'         => $total,
                            'letter_grade'  => $calculated['letter_grade'],
                            'gpa_points'    => $calculated['gpa_points'],
                        ]
                    );
                }
            }

            $this->command->info('    ✓ ' . count($subjectList) . ' subjects seeded with grades');
        }

        $this->command->info('');
        $this->command->info('✓ Student Portal seeding complete!');
        $this->command->info('  Semester : Spring 2026 (current)');
        $this->command->info('  Students : ' . $students->count() . ' processed');
        $this->command->info('  Grading  : Midterm1(20) + Midterm2(20) + Final(60) = 100');
        $this->command->info('  Buildings: 5 (IT/CS) | 9 (Humanities) | 1 (Engineering) | 2 (Sports)');
    }
}
