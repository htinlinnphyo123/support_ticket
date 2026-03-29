<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Organisation;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some organisations
        $organisations = Organisation::all();
        
        if ($organisations->count() > 0) {
            foreach ($organisations as $org) {
                for ($i = 1; $i <= 3; $i++) {
                    User::factory()->create([
                        'name' => 'Employee ' . $i,
                        'email' => 'employee' . $i . '@' . strtolower(str_replace(' ', '', $org->name)) . '.com',
                        'type' => \App\Enums\UserType::Employee->value,
                        'organisation_id' => $org->id,
                    ]);
                }
            }
        }
        
        // Also create some users without organisation (e.g. agents)
        for ($i = 1; $i <= 5; $i++) {
            User::factory()->create([
                'name' => 'Agent ' . $i,
                'email' => 'agent' . $i . '@gmail.com',
                'type' => \App\Enums\UserType::Agent->value,
                'organisation_id' => null,
            ]);
        }
    }
}
