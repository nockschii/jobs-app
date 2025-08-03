<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\Company;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        if (Company::count() === 0) {
            Company::factory(10)->create();
        }

        $companies = Company::all();

        $jobTitles = [
            'Senior PHP Developer',
            'Frontend React Developer',
            'Backend Node.js Developer',
            'Full Stack Developer',
            'DevOps Engineer',
            'Data Scientist',
            'UX/UI Designer',
            'Product Manager',
            'Marketing Manager',
            'Sales Representative',
            'HR Manager',
            'Financial Analyst',
            'Business Analyst',
            'Project Manager',
            'Quality Assurance Engineer',
            'Mobile App Developer',
            'Cloud Architect',
            'Cybersecurity Specialist',
            'Technical Writer',
            'Customer Success Manager',
            'Operations Manager',
            'Legal Counsel',
            'Graphic Designer',
            'Content Creator',
            'SEO Specialist',
            'Social Media Manager',
            'Machine Learning Engineer',
            'Database Administrator',
            'System Administrator',
            'Network Engineer',
            'Software Architect',
            'Scrum Master',
            'Product Owner',
            'Research Scientist',
            'Sales Manager',
            'Account Manager',
            'Business Development Manager',
            'Marketing Coordinator',
            'HR Coordinator',
            'Finance Manager',
            'Operations Coordinator',
            'IT Support Specialist',
            'Frontend Vue.js Developer',
            'Backend Python Developer',
            'Java Developer',
            'C# Developer',
            'iOS Developer',
            'Android Developer',
            'Game Developer',
            'Blockchain Developer'
        ];

        $europeanCities = [
            ['city' => 'Berlin', 'country' => 'Germany'],
            ['city' => 'Munich', 'country' => 'Germany'],
            ['city' => 'Hamburg', 'country' => 'Germany'],
            ['city' => 'Cologne', 'country' => 'Germany'],
            ['city' => 'Frankfurt', 'country' => 'Germany'],
            ['city' => 'Paris', 'country' => 'France'],
            ['city' => 'Lyon', 'country' => 'France'],
            ['city' => 'Marseille', 'country' => 'France'],
            ['city' => 'London', 'country' => 'United Kingdom'],
            ['city' => 'Manchester', 'country' => 'United Kingdom'],
            ['city' => 'Edinburgh', 'country' => 'United Kingdom'],
            ['city' => 'Amsterdam', 'country' => 'Netherlands'],
            ['city' => 'Rotterdam', 'country' => 'Netherlands'],
            ['city' => 'Madrid', 'country' => 'Spain'],
            ['city' => 'Barcelona', 'country' => 'Spain'],
            ['city' => 'Rome', 'country' => 'Italy'],
            ['city' => 'Milan', 'country' => 'Italy'],
            ['city' => 'Vienna', 'country' => 'Austria'],
            ['city' => 'Zurich', 'country' => 'Switzerland'],
            ['city' => 'Geneva', 'country' => 'Switzerland'],
            ['city' => 'Stockholm', 'country' => 'Sweden'],
            ['city' => 'Copenhagen', 'country' => 'Denmark'],
            ['city' => 'Oslo', 'country' => 'Norway'],
            ['city' => 'Helsinki', 'country' => 'Finland'],
            ['city' => 'Brussels', 'country' => 'Belgium'],
            ['city' => 'Prague', 'country' => 'Czech Republic'],
            ['city' => 'Warsaw', 'country' => 'Poland'],
            ['city' => 'Budapest', 'country' => 'Hungary'],
            ['city' => 'Dublin', 'country' => 'Ireland'],
            ['city' => 'Lisbon', 'country' => 'Portugal'],
            ['city' => 'Athens', 'country' => 'Greece'],
            ['city' => 'Bucharest', 'country' => 'Romania'],
            ['city' => 'Sofia', 'country' => 'Bulgaria'],
            ['city' => 'Zagreb', 'country' => 'Croatia'],
            ['city' => 'Ljubljana', 'country' => 'Slovenia'],
            ['city' => 'Bratislava', 'country' => 'Slovakia'],
            ['city' => 'Tallinn', 'country' => 'Estonia'],
            ['city' => 'Riga', 'country' => 'Latvia'],
            ['city' => 'Vilnius', 'country' => 'Lithuania'],
            ['city' => 'Luxembourg', 'country' => 'Luxembourg'],
            ['city' => 'Reykjavik', 'country' => 'Iceland'],
            ['city' => 'Malta', 'country' => 'Malta'],
            ['city' => 'Nicosia', 'country' => 'Cyprus'],
            ['city' => 'Belgrade', 'country' => 'Serbia'],
            ['city' => 'Sarajevo', 'country' => 'Bosnia and Herzegovina'],
            ['city' => 'Skopje', 'country' => 'North Macedonia'],
            ['city' => 'Tirana', 'country' => 'Albania'],
            ['city' => 'Podgorica', 'country' => 'Montenegro'],
            ['city' => 'Kiev', 'country' => 'Ukraine'],
            ['city' => 'Minsk', 'country' => 'Belarus']
        ];

        $departments = ['Engineering', 'Marketing', 'Sales', 'HR', 'Finance', 'Operations', 'Legal', 'Design'];
        $hoursPerWeek = ['Full-time (38.5)', 'Full-time (40)', 'Part-time (20)', 'Part-time (32)', 'Contract'];

        if ($companies->isNotEmpty()) {
            for ($i = 0; $i < 50; $i++) {
                $location = $europeanCities[array_rand($europeanCities)];

                // Using modulo: gleichmäßig auf alle 7 Tage verteilt
                $daysAgo = $i % 7; // Job 0,7,14... = heute, Job 1,8,15... = gestern, etc. und nicht 0-6, ...

                $createdAt = now()->subDays($daysAgo);

                Job::factory()->create([
                    'company_id' => $companies->random()->id,
                    'title' => $jobTitles[$i],
                    'description' => fake()->paragraphs(3, true),
                    'department' => $departments[array_rand($departments)],
                    'city' => $location['city'],
                    'country' => $location['country'],
                    'hours_per_week' => $hoursPerWeek[array_rand($hoursPerWeek)],
                    'is_active' => $i < 45 ? true : false,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }
    }
}
