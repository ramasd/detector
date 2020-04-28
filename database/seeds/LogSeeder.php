<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Project;
use App\User;

class LogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $projectsIds = Project::all()->pluck('id')->toArray();
        $usersIds = User::all()->pluck('id')->toArray();

        foreach (range(1,3) as $index) {
            $load_time = $randomFloat = rand(1, 300) / 100;
            $server_ip = long2ip(rand(0, "4294967295"));
            $redirect_detected = rand(0,1);

            DB::table('logs')->insert([
                'project_id' => $faker->randomElement($projectsIds),
                'user_id' => $faker->randomElement($usersIds),
                'data' => '{"status":200,"load_time":'.$load_time.',"server_ip":"'.$server_ip.'","redirect_detected":'.$redirect_detected.'}',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
