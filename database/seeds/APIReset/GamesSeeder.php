<?php
namespace APIReset;

use Illuminate\Database\Seeder;
use GameScores\Models\Game;
use DB;

class GamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("TRUNCATE TABLE games CASCADE");

        $games = [
            [
                'name' => 'Donkey Kong',
            ],
            [
                'name' => 'Mario Kart',
            ],
        ];

        foreach ($games as $game) {
            Game::create($game);
        }
    }
}
