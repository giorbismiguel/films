<?php

use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('comments')->insert([
            [
                'name' => 'First Comment',
                'comment' => 'Emma Watson just shared the first official poster for the live-action Disney remake of Beauty and the Beast, and fans are already going wild. In just a few hours, her Facebook post garnered almost 200,000 likes. But don’t get too excited just yet, as the movie won’t be out until next March.',
                'film_id' => 1
            ],
            [
                'name' => 'First Comment',
                'comment' => 'There\'s a little bit of a moral about being inclusive, but that\'s not why this movie was made: It\'s about angry outbursts, birds catapulting through the air, and big explosions.',
                'film_id' => 2
            ], [
                'name' => 'First Comment',
                'comment' => 'A hybrid between a historical epic and an action fantasy, the film manages to be only a passable example of each genre, which makes it less memorable than it had the potential to be.',
                'film_id' => 3
            ]
        ]);
    }
}
