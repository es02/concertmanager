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
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
            'genre' => 'Drone Doom',
        ]);

        Artist::factory()->create([
            'name' => 'The Waggles',
            'email' => 'swaggles@example.com',
            'location' => 'Brisbane',
            'standard_fee' => 300,
            'bio' => 'Here for a waggly good time!',
            'genre' => 'Childrens'
        ]);

        Artist::factory()->create([
            'name' => 'Misery Division',
            'email' => 'sadness@example.com',
            'location' => 'Brisbane',
            'standard_fee' => 30000,
            'bio' => 'Recently returned from headline performances at such nonexistent festivals as Inala OpenAir and MelonStock Misery Division are here to rock your next event!',
            'genre' => 'Post Punk'
        ]);

        Event::factory()->create([
            'name' => 'Somewhere Festival',
            'venue_id' => 1,
        ]);

        Event_Stage::factory()->create([
            'event_id' => 1,
            'venue_id' => 1,
        ]);

        Event_Set::factory()->create([
            'event_id' => 1,
            'venue_id' => 1,
            'event_stage_id' => 1,
            'artist_id' => 3,
        ]);

        Event_Set::factory()->create([
            'event_id' => 1,
            'venue_id' => 1,
            'event_stage_id' => 1,
            'artist_id' => 1,
        ]);

        Event_Set::factory()->create([
            'event_id' => 1,
            'venue_id' => 1,
            'event_stage_id' => 1,
            'artist_id' => 2,
        ]);

        // Users can have multiple roles
        $god = Role::create(['name' => 'god']);            // Global superuser
        $agent = Role::create(['name' => 'booking_agent']);  // can edit multiple artists
        $artist = Role::create(['name' => 'artist']);         // can edit own artist page
        $venue = Role::create(['name' => 'venue']);          // can edit own venue page
        $media = Role::create(['name' => 'media']);          // can edit own media page
        $sponsor = Role::create(['name' => 'sponsor']);        // can edit own sponsor page
        $volunteer = Role::create(['name' => 'volunteer']);      // can edit own volunteer page
        $staff = Role::create(['name' => 'staff']);          // can edit own paid staff member page

        // roles for user types to edit their own data
        $permission = Permission::create(['name' => 'edit own artist']);
        $permission->assignRole($artist);
        $permission = Permission::create(['name' => 'edit own venue']);
        $permission->assignRole($venue);
        $permission = Permission::create(['name' => 'edit own media']);
        $permission->assignRole($media);
        $permission = Permission::create(['name' => 'edit own sponsor']);
        $permission->assignRole($sponsor);
        $permission = Permission::create(['name' => 'edit own volunteer']);
        $permission->assignRole($volunteer);
        $permission = Permission::create(['name' => 'edit own staff']);
        $permission->assignRole($staff);
        $permission = Permission::create(['name' => 'edit shared artist']);
        $permission->assignRole($agent);

        // everyone on an event should have read-only access to the event
        // TODO: Make this more fine grained
        $permission = Permission::create(['name' => 'view own event']);
        $permission->assignRole($artist);
        $permission->assignRole($venue);
        $permission->assignRole($media);
        $permission->assignRole($sponsor);
        $permission->assignRole($volunteer);
        $permission->assignRole($staff);
        $permission->assignRole($agent);

        // Super-user has full access to everything
        // doesn't need multiple roles
        $permission = Permission::create(['name' => 'edit all artist']);
        $permission->assignRole($god);
        $permission = Permission::create(['name' => 'edit all venue']);
        $permission->assignRole($god);
        $permission = Permission::create(['name' => 'edit all media']);
        $permission->assignRole($god);
        $permission = Permission::create(['name' => 'edit all sponsor']);
        $permission->assignRole($god);
        $permission = Permission::create(['name' => 'edit all volunteer']);
        $permission->assignRole($god);
        $permission = Permission::create(['name' => 'edit email integration']);
        $permission->assignRole($god);
        $permission = Permission::create(['name' => 'edit all event']);
        $permission->assignRole($god);
        $permission = Permission::create(['name' => 'edit all task']);
        $permission->assignRole($god);
    }
}
