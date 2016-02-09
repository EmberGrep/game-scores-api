<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use GameScores\Models\Game;
use GameScores\Models\GameScore;

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

        $this->assertEquals('AAA', GameScore::firstOrFail()->username);
    }

    public function testCannotCreateScoreForInvalidGame()
    {
        $id = $this->game->id;
        $this->game->delete();

        $this->json('POST', 'game-scores', ['data' => [
            'type' => 'game-score',
            'attributes' => [
                'username' => 'AAA',
                'score' => 1000000,
                'game' => $id,
            ],
        ]]);

        $this->assertResponseStatus(400);

        $this->seeJson([
            'errors' => [
                [
                    'status' => '400',
                    'title' => 'Invalid Attribute',
                    'detail' => 'The selected game is invalid.'
                ],
            ],
        ]);
    }

    public function testUpdateGameScore()
    {
        $gameScore = GameScore::create([
            'username' => 'AAA',
            'score' => 1000000,
            'game' => $this->game->id,
        ]);

        $this->json('PUT', "game-scores/{$gameScore->id}", ['data' => [
            'type' => 'game-score',
            'id' => $gameScore->id,
            'attributes' => [
                'username' => 'AAA',
                'score' => 2000000,
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
                    'score' => 2000000,
                    'game' => $this->game->id,
                ],
            ],
        ]);

        $this->assertEquals(2000000, GameScore::firstOrFail()->score);
    }
}
