<?php
namespace APIReset;

use Illuminate\Database\Seeder;
use GameScores\Models\GameScore;

class GameScoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GameScore::truncate();

        $gameScores = [
            [
                'username' => 'AAA',
                'score' => 1000,
                'game' => 1,
            ],
            [
                'username' => 'CDC',
                'score' => 1500,
                'game' => 1,
            ],
            [
                'username' => 'RFT',
                'score' => 2000,
                'game' => 1,
            ],
        ];

        foreach ($gameScores as $gameScore) {
            GameScore::create($gameScore);
        }
    }
}
