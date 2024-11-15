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
            'default' => 1,
            'state' => 'active',
        ]);

        $url = str_replace(['http://', 'https://', 'HTTP://', 'HTTPS://'], '', env('APP_URL', 'example.com'));

        Tenant::factory()->create([
            'name' => 'Default',
            'fqdn' => $url,
            'state' => 'active',
        ]);

        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => env('MAIL_FROM_ADDRESS', 'test@example.com'),
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

        $user->assignRole('god');
    }
}
