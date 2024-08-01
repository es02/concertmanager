<?php

namespace Database\Seeders;

use App\Models\Artist;
use App\Models\Event;
use App\Models\Event_Application;
use App\Models\Event_Application_Field;
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
            'venue_name' => 'KLT',
            'email' => 'klt@example.com',
            'capacity' => 80,
            'location' => 'Fortitude Valley',
            'standard_fee' => 150,
            'fee_type' => 'total',
        ]);

        Venue::factory()->create([
            'venue_name' => 'BLB',
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
            'genre' => 'Childrens',
        ]);

        Artist::factory()->create([
            'name' => 'Misery Division',
            'email' => 'sadness@example.com',
            'location' => 'Brisbane',
            'standard_fee' => 30000,
            'bio' => 'Recently returned from headline performances at such nonexistent festivals as Inala OpenAir and MelonStock Misery Division are here to rock your next event!',
            'genre' => 'Post Punk',
        ]);

        Event::factory()->create([
            'name' => 'Somewhere Festival',
            'venue_id' => 2,
            'location' => 'Brisbane',
            'description' => 'Get ready to rock out at the Somewhere Festival! This electrifying metal event promises bone-crushing riffs, thunderous drums, and an unforgettable atmosphere. Join us for a night of raw energy and powerful performances from some of the genre\'s best bands. Don\'t miss the metal mayhem at Somewhere Festival!',
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

        Event_Application::factory()->create([
            'event_id' => 1,
            'name' => 'foo',
            'type' => 'artist',
            'published' => 1,
        ]);

        Event_Application_Field::factory()->create([
            'event_id' => 1,
            'event_application_id' => 1,
            'order_id' => 1,
            'name' => 'Artist Name',
            'description' => '',
            'expected_value' => 'string',
            'mapped_value' => 'name',
            'mandatory' => 1,
        ]);

        Event_Application_Field::factory()->create([
            'event_id' => 1,
            'event_application_id' => 1,
            'order_id' => 2,
            'name' => 'Contact Email',
            'description' => '',
            'expected_value' => 'string',
            'mapped_value' => 'email',
            'mandatory' => 1,
        ]);

        Event_Application_Field::factory()->create([
            'event_id' => 1,
            'event_application_id' => 1,
            'order_id' => 3,
            'name' => 'Genre(s)',
            'description' => 'What sort of heavy music do you play?',
            'expected_value' => 'string',
            'mapped_value' => 'genre',
            'mandatory' => 1,
        ]);

        Event_Application_Field::factory()->create([
            'event_id' => 1,
            'event_application_id' => 1,
            'order_id' => 6,
            'name' => 'Artist Bio',
            'description' => 'Please tell us about your band and why we should book you.
            This will also help us with writing descriptions for promo material in the event that your application is successfull.
            If your band features diverse members this is a good place to tell us about it.',
            'expected_value' => 'longText',
            'mapped_value' => 'bio',
            'mandatory' => 1,
        ]);

        Event_Application_Field::factory()->create([
            'event_id' => 1,
            'event_application_id' => 1,
            'order_id' => 4,
            'name' => 'Band Location',
            'description' => 'Where in Australia or the world are you located?',
            'expected_value' => 'enum[QLD-BNE, QLD, NSW, ACT, VIC, SA, WA, TAS, NT, OTHER]',
            'mapped_value' => 'location',
            'mandatory' => 1,
        ]);

        Event_Application_Field::factory()->create([
            'event_id' => 1,
            'event_application_id' => 1,
            'order_id' => 5,
            'name' => 'Location - other:',
            'description' => 'If you selected OTHER as your location, please tell us where in the world you are based.',
            'expected_value' => 'string',
            'mandatory' => 0,
        ]);

        Event_Application_Field::factory()->create([
            'event_id' => 1,
            'event_application_id' => 1,
            'order_id' => 7,
            'name' => 'Social Media URL(s)',
            'description' => 'Please provide at least one social media URL to help determine your audience reach',
            'expected_value' => 'longText',
            'mandatory' => 1,
        ]);

        Event_Application_Field::factory()->create([
            'event_id' => 1,
            'event_application_id' => 1,
            'order_id' => 8,
            'name' => 'Music URL(s)',
            'description' => 'Please provide at least one piece of music for us to to help determine your sound',
            'expected_value' => 'longText',
            'mandatory' => 1,
        ]);

        Event_Application_Field::factory()->create([
            'event_id' => 1,
            'event_application_id' => 1,
            'order_id' => 10,
            'name' => 'Expected renumeration',
            'description' => 'Please let us know what you would expect to be paid for an appearance.
            Note: We do not guarentee we will be able to offer you this amount, however this is a starting point for negotiations and helps us to determine whether or not you are within our budget.',
            'expected_value' => 'string',
            'mandatory' => 1,
        ]);

        Event_Application_Field::factory()->create([
            'event_id' => 1,
            'event_application_id' => 1,
            'order_id' => 9,
            'name' => 'EPK',
            'description' => 'If you have an EPK please provide a link to it here.',
            'expected_value' => 'string',
            'mapped_value' => 'epk_url',
            'mandatory' => 0,
        ]);

        Event_Application_Field::factory()->create([
            'event_id' => 1,
            'event_application_id' => 1,
            'order_id' => 11,
            'name' => 'Technical Requirements',
            'description' => 'Please let us know if you have any special technical requirements, eg: DI boxes, IEMs, pyro, etc',
            'expected_value' => 'longText',
            'mapped_value' => 'tech_specs',
            'mandatory' => 0,
        ]);

        Event_Application_Field::factory()->create([
            'event_id' => 1,
            'event_application_id' => 1,
            'order_id' => 12,
            'name' => 'Additional Requirements',
            'description' => 'Please let us know if you have any further needs including hospitality rider',
            'expected_value' => 'longText',
            'mapped_value' => 'standard_rider',
            'mandatory' => 0,
        ]);

        Event_Application_Field::factory()->create([
            'event_id' => 1,
            'event_application_id' => 1,
            'order_id' => 13,
            'name' => 'Preferred timeslot',
            'description' => 'Do you have any specific timeslot needs?
            Please note that we cannot guarentee we will be able to honour timeslot requests but we will take them into consideration when determining set times.',
            'expected_value' => 'enum[Any, Early, Middle, Late, Headline]',
            'mandatory' => 0,
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
