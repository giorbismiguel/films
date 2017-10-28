<?php

use Illuminate\Database\Seeder;

class FilmsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('films')->insert([
            [
                'id' => 1,
                'name' => 'Beauty And The Beast',
                'description' => 'Belle (Emma Watson), a bright, beautiful and independent young woman, is taken prisoner by a beast (Dan Stevens) in its castle. Despite her fears, she befriends the castle\'s enchanted staff and learns to look beyond the beast\'s hideous exterior, allowing her to recognize the kind heart and soul of the true prince that hides on the inside.',
                'rating' => 5,
                'ticket_price' => 50,
                'release_at' => \Carbon\Carbon::now(),
                'country_id' => 581,
                'photo' => 'Beauty And The Beast Poster Latino.jpg',
                'slug' => str_slug('Beauty And The Beast')
            ],
            [
                'id' => 2,
                'name' => 'The Angry Birds',
                'description' => 'Flightless birds lead a mostly happy existence, except for Red (Jason Sudeikis), who just can\'t get past the daily annoyances of life. His temperament leads him to anger management class, where he meets fellow misfits Chuck (Josh Gad) and Bomb. Red becomes even more agitated when his feathered brethren welcome green pigs to their island paradise. As the swine begin to get under his skin, Red joins forces with Chuck and Bomb to investigate the real reason behind their mysterious arrival.',
                'rating' => 5,
                'ticket_price' => 60,
                'release_at' => \Carbon\Carbon::now(),
                'country_id' => 581,
                'photo' => 'The Angry Birds.jpg',
                'slug' => str_slug('The Angry Birds')
            ],
            [
                'id' => 3,
                'name' => 'The Great Wall',
                'description' => 'When a mercenary warrior (Matt Damon) is imprisoned within the Great Wall, he discovers the mystery behind one of the greatest wonders of the world. As wave after wave of marauding beasts besiege the massive structure, his quest for fortune turns into a journey toward heroism as he joins a huge army…',
                'rating' => 5,
                'ticket_price' => 70,
                'release_at' => \Carbon\Carbon::now(),
                'country_id' => 581,
                'photo' => 'The Great Wall.jpg',
                'slug' => str_slug('The Great Wall')
            ]
        ]);

        DB::table('film_genre')->insert([
            [
                'film_id' => 1,
                'genre_id' => 3,
            ],
            [
                'film_id' => 2,
                'genre_id' => 3,
            ],
            [
                'film_id' => 2,
                'genre_id' => 1,
            ],
            [
                'film_id' => 3,
                'genre_id' => 3,
            ]
        ]);

    }
}
