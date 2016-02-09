<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use GameScores\Models\Game;

class GameScoreApiTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    protected $gameAttrs = ['name' => 'Donkey Kong Country'];
    protected $gameAttrsTwo = ['name' => 'Mario Kart'];
    protected $game;
    protected $gameTwo;

    public function setUp() {
        parent::setUp();

        $this->game = Game::create($this->gameAttrs);
        $this->gameTwo = Game::create($this->gameAttrsTwo);
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testCreateGameScore()
    {
        $this->json('POST', 'game-scores', ['data' => [
            'type' => 'game-score',
            'attributes' => [
                'username' => 'AAA',
                'score' => 1000000,
                'game' => $this->game->id,
            ],
        ]]);

        $this->assertResponseOk();

        $this->seeJson([
            'data' => [
                'type' => 'game-score',
                'id' => '1',
                'attributes' => [
                    'username' => 'AAA',
                    'score' => 1000000,
                    'game' => $this->game->id,
                ],
            ],
        ]);

        $this->assertEquals($this->gameAttrs['name'], Game::firstOrFail()->name);
    }
}
