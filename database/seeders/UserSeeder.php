<?php

namespace Database\Seeders;

use App\Enums\ROLE;
use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if (env('APP_ENV') === 'prod') {
            $admin = User::firstOrCreate(
                ['email' => 'admin@cysc.fr'],
                ['full_name' => 'Admin', 'password' => bcrypt('fnFPB3TzGWTBoLA')],
            );
            $admin->assignRole(ROLE::ADMIN);

            $user = User::firstOrCreate(
                ['email' => 'user@cysc.fr'],
                ['full_name' => 'User', 'password' => bcrypt('nRapnRYRdxcE')],
            );
            $user->assignRole(ROLE::USER);
        } else {
            $admin = User::firstOrCreate(
                ['email' => 'admin@cysc.fr'],
                ['full_name' => 'Admin', 'password' => bcrypt('admin')],
            );
            $admin->assignRole(ROLE::ADMIN);
            $user = User::firstOrCreate(
                ['email' => 'user@cysc.fr'],
                ['full_name' => 'User', 'password' => bcrypt('user')],
            );
            $user->assignRole(ROLE::USER);
        }

        $categories = Category::factory(10)->create();

        $users = User::factory(5)->create();

        $users->each(function ($user) use ($categories) {
            Event::factory(rand(1, 2))->create([
                'host_id' => $user->id,
                'category_id' => $categories->random()->id,
            ]);
        });

        $events = Event::all();

        $users->each(function ($user) use ($events) {
            $user->participatingIn()->attach(
                $events->where('host_id', '!=', $user->id)->random(rand(1, 3))->pluck('id')->toArray(),
            );
        });
    }
}
