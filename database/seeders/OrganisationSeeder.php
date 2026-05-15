<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Database\Seeder;

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
            'type' => UserType::Agent->value,
            'organisation_id' => null,
        ]);

        for ($i = 1; $i <= 5; $i++) {
            Organisation::factory()->create([
                'name' => 'Organisation '.$i,
                'description' => 'Description '.$i,
                'status' => 'active',
                'created_by' => $superAgent->id,
            ]);
        }
    }
}
