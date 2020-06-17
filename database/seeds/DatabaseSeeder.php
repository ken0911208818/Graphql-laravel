<?php

use App\Post;
use App\User;
use App\Comment;
use App\Job;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        factory(Comment::class, 20)->create();
        User::all()->each(function ($user) use ($faker) {
            foreach (range(1, 5) as $i) {
                $status = 'QUEUED';

                if ($i == 4) {
                    $status = 'PROCESSING';
                }
                if ($i == 5) {
                    $status = 'COMPLETE';
                }
                Job::create([
                    'user_id' => $user->getKey(),
                    'title' => $faker->sentence,
                    'status' => $status,
                ]);
            }
        });

    }
}
