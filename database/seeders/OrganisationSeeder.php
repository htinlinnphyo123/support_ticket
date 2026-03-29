<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Organisation;
use App\Models\User;

class OrganisationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, create a system admin / super agent to be the creator of organisations
        $superAgent = User::factory()->create([
            'name' => 'Super Admin',
            'type' => \App\Enums\UserType::Agent->value,
            'organisation_id' => null,
        ]);

        for ($i = 1; $i <= 5; $i++) {
            Organisation::factory()->create([
                'name' => 'Organisation ' . $i,
                'description' => 'Description ' . $i,
                'status' => 'active',
                'created_by' => $superAgent->id,
            ]);
        }
    }
}
