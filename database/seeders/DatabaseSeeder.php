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
            'venue_name' => 'KLT',
            'email' => 'klt@example.com',
            'capacity' => 80,
            'location' => 'Fortitude Valley',
            'standard_fee' => 150,
            'fee_type' => 'total',
            'pic_url' => 'https://scontent.fbne8-1.fna.fbcdn.net/v/t39.30808-6/318338413_595318659259896_5735478324142805071_n.jpg?_nc_cat=106&ccb=1-7&_nc_sid=f727a1&_nc_ohc=TmHunPyxrbUQ7kNvgGMJF1M&_nc_ht=scontent.fbne8-1.fna&oh=00_AYAkA7vlnAWHWcCLyR4suxDeaxeSzOK02wZJr2dQlkOC4w&oe=669BA06B'
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
            'pic_url' => 'https://scontent.fbne8-1.fna.fbcdn.net/v/t39.30808-6/338034733_561269432777374_3107056735531816269_n.jpg?_nc_cat=107&ccb=1-7&_nc_sid=833d8c&_nc_ohc=wHHyJVB__YAQ7kNvgGt4qOH&_nc_ht=scontent.fbne8-1.fna&oh=00_AYB8p4KXQk1MEYtWeSUbNeLKpPiDY-Dx56FjPiBYmsOelw&oe=669BA172'
        ]);

        Artist::factory()->create([
            'name' => 'SoP',
            'email' => 'sop@example.com',
            'location' => 'Brisbane',
            'standard_fee' => 100,
            'bio' => 'Ambient noise',
            'genre' => 'Drone Doom',
            'pic_url' => 'https://scontent.fbne8-1.fna.fbcdn.net/v/t39.30808-6/444225145_907177851422232_7430636100667057655_n.jpg?_nc_cat=106&ccb=1-7&_nc_sid=833d8c&_nc_ohc=D5izsIXuoV0Q7kNvgEyOv9t&_nc_ht=scontent.fbne8-1.fna&cb_e2o_trans=q&oh=00_AYA7m_soo-hif4WY19Zk13i3JCceG83ypoTFW82BthNwlA&oe=669BB1A1',
        ]);

        Artist::factory()->create([
            'name' => 'The Waggles',
            'email' => 'swaggles@example.com',
            'location' => 'Brisbane',
            'standard_fee' => 300,
            'bio' => 'Here for a waggly good time!',
            'genre' => 'Childrens',
            'pic_url' => 'https://scontent.fbne8-1.fna.fbcdn.net/v/t1.6435-9/120040264_930053277483535_6083338566300930881_n.jpg?_nc_cat=110&ccb=1-7&_nc_sid=f798df&_nc_ohc=-iluATm7cl4Q7kNvgGJQ2LD&_nc_ht=scontent.fbne8-1.fna&oh=00_AYDiVXkCx02Hqynjj9eOIHLo_OGMiUNWNb0gZJ_VXMxnVA&oe=66BD5D8D'
        ]);

        Artist::factory()->create([
            'name' => 'Misery Division',
            'email' => 'sadness@example.com',
            'location' => 'Brisbane',
            'standard_fee' => 30000,
            'bio' => 'Recently returned from headline performances at such nonexistent festivals as Inala OpenAir and MelonStock Misery Division are here to rock your next event!',
            'genre' => 'Post Punk',
            'pic_url' => 'https://scontent.fbne8-1.fna.fbcdn.net/v/t39.30808-6/285800945_1335334950288697_3485091587621045446_n.jpg?_nc_cat=106&ccb=1-7&_nc_sid=f727a1&_nc_ohc=5WIiezIzJZEQ7kNvgErByRV&_nc_ht=scontent.fbne8-1.fna&oh=00_AYAFM_WyqB9wsMno6jBbImX3yqZY2ztem82eYREiUw6pLA&oe=669BB5CB'
        ]);

        Event::factory()->create([
            'name' => 'Somewhere Festival',
            'venue_id' => 2,
            'location' => 'Brisbane',
            'description' => 'Get ready to rock out at the Somewhere Festival! This electrifying metal event promises bone-crushing riffs, thunderous drums, and an unforgettable atmosphere. Join us for a night of raw energy and powerful performances from some of the genre\'s best bands. Don\'t miss the metal mayhem at Somewhere Festival!',
            'event_pic_url' => 'https://scontent.fbne8-1.fna.fbcdn.net/v/t39.30808-6/451077583_1072396861408561_8124381039737960974_n.png?_nc_cat=111&ccb=1-7&_nc_sid=cc71e4&_nc_ohc=rLbf7fA9T_EQ7kNvgGAlnnE&_nc_ht=scontent.fbne8-1.fna&oh=00_AYBsLhLLxJ-ZHuvBQn_F3GmwLA6pSxJIz7oihO45-wVO6g&oe=669BAC82'
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
