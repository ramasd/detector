<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\User;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $usersIds = User::all()->pluck('id')->toArray();

        foreach (range(1,3) as $index) {
            DB::table('projects')->insert([
                'name' => Str::random(10),
                'url' => 'https://www.' . Str::random(10) . '.com',
                'status' => 1,
                'user_id' => $faker->randomElement($usersIds),
                'check_frequency' => 20,
                'last_check' => Carbon::now()->addHour(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
