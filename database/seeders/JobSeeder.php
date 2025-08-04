<?php

namespace Database\Seeders;

use App\Enums\EmploymentType;
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
            'Senior PHP Entwickler',
            'Frontend React Entwickler',
            'Backend Node.js Entwickler',
            'Full Stack Entwickler',
            'DevOps Engineer',
            'Data Scientist',
            'UX/UI Designer',
            'Produktmanager',
            'Marketing Manager',
            'Vertriebsmitarbeiter',
            'HR Manager',
            'Finanzanalyst',
            'Business Analyst',
            'Projektmanager',
            'Quality Assurance Engineer',
            'Mobile App Entwickler',
            'Cloud Architekt',
            'Cybersecurity Spezialist',
            'Technical Writer',
            'Customer Success Manager',
            'Operations Manager',
            'Rechtsberater',
            'Grafikdesigner',
            'Content Creator',
            'SEO Spezialist',
            'Social Media Manager',
            'Machine Learning Engineer',
            'Datenbankadministrator',
            'Systemadministrator',
            'Netzwerk Engineer',
            'Software Architekt',
            'Scrum Master',
            'Product Owner',
            'Forschungswissenschaftler',
            'Vertriebsleiter',
            'Account Manager',
            'Business Development Manager',
            'Marketing Koordinator',
            'HR Koordinator',
            'Finance Manager',
            'Operations Koordinator',
            'IT Support Spezialist',
            'Frontend Vue.js Entwickler',
            'Backend Python Entwickler',
            'Java Entwickler',
            'C# Entwickler',
            'iOS Entwickler',
            'Android Entwickler',
            'Game Developer',
            'Blockchain Entwickler'
        ];

        $europeanCities = [
            ['city' => 'Berlin', 'country' => 'Deutschland'],
            ['city' => 'München', 'country' => 'Deutschland'],
            ['city' => 'Hamburg', 'country' => 'Deutschland'],
            ['city' => 'Köln', 'country' => 'Deutschland'],
            ['city' => 'Frankfurt', 'country' => 'Deutschland'],
            ['city' => 'Paris', 'country' => 'Frankreich'],
            ['city' => 'Lyon', 'country' => 'Frankreich'],
            ['city' => 'Marseille', 'country' => 'Frankreich'],
            ['city' => 'London', 'country' => 'Vereinigtes Königreich'],
            ['city' => 'Manchester', 'country' => 'Vereinigtes Königreich'],
            ['city' => 'Edinburgh', 'country' => 'Vereinigtes Königreich'],
            ['city' => 'Amsterdam', 'country' => 'Niederlande'],
            ['city' => 'Rotterdam', 'country' => 'Niederlande'],
            ['city' => 'Madrid', 'country' => 'Spanien'],
            ['city' => 'Barcelona', 'country' => 'Spanien'],
            ['city' => 'Rom', 'country' => 'Italien'],
            ['city' => 'Mailand', 'country' => 'Italien'],
            ['city' => 'Wien', 'country' => 'Österreich'],
            ['city' => 'Zürich', 'country' => 'Schweiz'],
            ['city' => 'Genf', 'country' => 'Schweiz'],
            ['city' => 'Stockholm', 'country' => 'Schweden'],
            ['city' => 'Kopenhagen', 'country' => 'Dänemark'],
            ['city' => 'Oslo', 'country' => 'Norwegen'],
            ['city' => 'Helsinki', 'country' => 'Finnland'],
            ['city' => 'Brüssel', 'country' => 'Belgien'],
            ['city' => 'Prag', 'country' => 'Tschechien'],
            ['city' => 'Warschau', 'country' => 'Polen'],
            ['city' => 'Budapest', 'country' => 'Ungarn'],
            ['city' => 'Dublin', 'country' => 'Irland'],
            ['city' => 'Lissabon', 'country' => 'Portugal'],
            ['city' => 'Athen', 'country' => 'Griechenland'],
            ['city' => 'Bukarest', 'country' => 'Rumänien'],
            ['city' => 'Sofia', 'country' => 'Bulgarien'],
            ['city' => 'Zagreb', 'country' => 'Kroatien'],
            ['city' => 'Ljubljana', 'country' => 'Slowenien'],
            ['city' => 'Bratislava', 'country' => 'Slowakei'],
            ['city' => 'Tallinn', 'country' => 'Estland'],
            ['city' => 'Riga', 'country' => 'Lettland'],
            ['city' => 'Vilnius', 'country' => 'Litauen'],
            ['city' => 'Luxemburg', 'country' => 'Luxemburg'],
            ['city' => 'Reykjavik', 'country' => 'Island'],
            ['city' => 'Malta', 'country' => 'Malta'],
            ['city' => 'Nikosia', 'country' => 'Zypern'],
            ['city' => 'Belgrad', 'country' => 'Serbien'],
            ['city' => 'Sarajevo', 'country' => 'Bosnien und Herzegowina'],
            ['city' => 'Skopje', 'country' => 'Nordmazedonien'],
            ['city' => 'Tirana', 'country' => 'Albanien'],
            ['city' => 'Podgorica', 'country' => 'Montenegro'],
            ['city' => 'Kiew', 'country' => 'Ukraine'],
            ['city' => 'Minsk', 'country' => 'Belarus']
        ];

        $departments = ['Entwicklung', 'Marketing', 'Vertrieb', 'Personalwesen', 'Finanzen', 'Operations', 'Recht', 'Design'];
        $employmentType = array_map(fn($type) => $type->value, EmploymentType::cases());

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
                    'employment_type' => $employmentType[array_rand($employmentType)],
                    'is_active' => $i < 45 ? true : false,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }
    }
}
