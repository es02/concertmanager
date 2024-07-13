<?php

namespace Database\Seeders;

use App\Models\Artist;
use App\Models\Event;
use App\Models\Event_Stage;
use App\Models\Event_Set;
use App\Models\Plan;
use App\Models\User;
use App\Models\Venue;
use App\Models\Tenant;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Plan::factory()->create([
            'name' => 'Default',
            'price' => 0,
        ]);

        Tenant::factory()->create([
            'name' => 'Default',
            'fqdn' => 'example.com',
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'test@example.com',
        ]);

        Venue::factory()->create([
            'name' => 'KLT',
            'email' => 'klt@example.com',
            'capacity' => 80,
            'location' => 'Fortitude Valley',
            'standard_fee' => 150,
            'fee_type' => 'total',
        ]);

        Venue::factory()->create([
            'name' => 'BLB',
            'email' => 'blb@example.com',
            'capacity' => 300,
            'location' => 'Fortitude Valley',
            'standard_fee' => 350,
            'fee_type' => 'minimum',
            'ticket_cut' => 4,
            'cut_type' => 'per_ticket',
        ]);

        Artist::factory()->create([
            'name' => 'SoP',
            'email' => 'sop@example.com',
            'location' => 'Brisbane',
            'standard_fee' => 100,
            'bio' => 'Ambient noise',
        ]);

        Artist::factory()->create([
            'name' => 'The Waggles',
            'email' => 'swaggles@example.com',
            'location' => 'Brisbane',
            'standard_fee' => 300,
            'bio' => 'Here for a waggly good time!',
        ]);

        Artist::factory()->create([
            'name' => 'Misery Division',
            'email' => 'sadness@example.com',
            'location' => 'Brisbane',
            'standard_fee' => 30000,
            'bio' => 'Recently returned from headline performances at such nonexistent festivals as Inala OpenAir and MelonStock Misery Division are here to rock your next event!',
        ]);

        Event::factory()->create([
            'name' => 'Somewhere Festival',
            'venue_id' => 1,
        ]);

        Event_Stage::factory()->create([
            'event_id' => 0,
            'venue_id' => 1,
        ]);

        Event_Set::factory()->create([
            'event_id' => 0,
            'venue_id' => 1,
            'event_stage_id' => 0,
            'artist_id' => 0,
        ]);

        Event_Set::factory()->create([
            'event_id' => 0,
            'venue_id' => 1,
            'event_stage_id' => 0,
            'artist_id' => 1,
        ]);

        Event_Set::factory()->create([
            'event_id' => 0,
            'venue_id' => 1,
            'event_stage_id' => 0,
            'artist_id' => 2,
        ]);
    }
}
