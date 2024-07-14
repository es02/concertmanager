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
            'pic_url' => 'https://discord.com/channels/@me/1130275080295952385/1261900557237489684'
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
            'pic_url' => 'https://media.discordapp.net/attachments/1130275080295952385/1261900624874836018/189921283_4326652770679202_3043278117418212882_n.png?ex=6694a409&is=66935289&hm=ee3930ede403345284eec615a2c13f62934d9d7309aca81fb9e245a59e790d94&=&format=webp&quality=lossless&width=1100&height=618'
        ]);

        Artist::factory()->create([
            'name' => 'SoP',
            'email' => 'sop@example.com',
            'location' => 'Brisbane',
            'standard_fee' => 100,
            'bio' => 'Ambient noise',
            'genre' => 'Drone Doom',
            'pic_url' => 'https://media.discordapp.net/attachments/1130275080295952385/1261842414696529930/SymphonyOfPutrescence04.png?ex=66946dd3&is=66931c53&hm=30e8bf1e2e5b7ad91a438619a0ecb26f83f1056dbd1f445b4582451fce81db98&=&format=webp&quality=lossless&width=1878&height=1056',
        ]);

        Artist::factory()->create([
            'name' => 'The Waggles',
            'email' => 'swaggles@example.com',
            'location' => 'Brisbane',
            'standard_fee' => 300,
            'bio' => 'Here for a waggly good time!',
            'genre' => 'Childrens',
            'pic_url' => 'https://media.discordapp.net/attachments/1130275080295952385/1261842485546848366/borodino2.jpg?ex=66946de4&is=66931c64&hm=8979761bcd078560b1db9efa12b7ca0bcf996d9fcd4610728eaa363dd46ecc82&=&format=webp&width=1052&height=700'
        ]);

        Artist::factory()->create([
            'name' => 'Misery Division',
            'email' => 'sadness@example.com',
            'location' => 'Brisbane',
            'standard_fee' => 30000,
            'bio' => 'Recently returned from headline performances at such nonexistent festivals as Inala OpenAir and MelonStock Misery Division are here to rock your next event!',
            'genre' => 'Post Punk',
            'pic_url' => 'https://media.discordapp.net/attachments/1130275080295952385/1261842626236125244/gonzales.jpg?ex=66946e05&is=66931c85&hm=c22a859bf3ed8384a73328efb970073b56234c23e3d5d57d07fac5a404c10013&=&format=webp&width=800&height=1202'
        ]);

        Event::factory()->create([
            'name' => 'Somewhere Festival',
            'venue_id' => 1,
            'location' => 'Brisbane',
            'description' => 'Get ready to rock out at the Somewhere Festival! This electrifying metal event promises bone-crushing riffs, thunderous drums, and an unforgettable atmosphere. Join us for a night of raw energy and powerful performances from some of the genre\'s best bands. Don\'t miss the metal mayhem at Somewhere Festival!',
            'pic_url' => 'https://media.discordapp.net/attachments/1130275080295952385/1261901339852537978/Poster-Square.png?ex=6694a4b4&is=66935334&hm=31a614d0fcb5559c77e5e8daf440fc80d8cd53df010a6ee57460afcc8beed240&=&format=webp&quality=lossless&width=700&height=700'
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
