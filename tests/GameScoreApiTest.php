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
            'type' => 'game-scores',
            'attributes' => [
                'username' => 'AAA',
                'score' => 1000000,
            ],
            'relationships' => [
                'game' => [
                    'data' => [
                        'type' => 'games',
                        'id' => (string) $this->game->id,
                    ],
                ],
            ],
        ]]);

        $this->assertResponseOk();

        $this->seeJson([
            'data' => [
                'type' => 'game-scores',
                'id' => '1',
                'attributes' => [
                    'username' => 'AAA',
                    'score' => 1000000,
                ],
                'relationships' => [
                    'game' => [
                        'data' => [
                            'type' => 'games',
                            'id' => (string) $this->game->id,
                        ],
                    ],
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
            'type' => 'game-scores',
            'attributes' => [
                'username' => 'AAA',
                'score' => 1000000,
            ],
            'relationships' => [
                'game' => [
                    'data' => [
                        'type' => 'games',
                        'id' => (string) $id,
                    ],
                ],
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

    public function testGetGameScore()
    {
        $gameScore = GameScore::create([
            'username' => 'AAA',
            'score' => 1000000,
            'game' => $this->game->id,
        ]);

        $this->json('GET', 'game-scores/1');

        $this->assertResponseOk();

        $this->seeJson([
            'data' => [
                'type' => 'game-scores',
                'id' => '1',
                'attributes' => [
                    'username' => 'AAA',
                    'score' => 1000000,
                ],
                'relationships' => [
                    'game' => [
                        'data' => [
                            'type' => 'games',
                            'id' => (string) $this->game->id,
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testGameScoreIndex()
    {
        GameScore::create([
            'username' => 'AAA',
            'score' => 1000000,
            'game' => $this->game->id,
        ]);
        GameScore::create([
            'username' => 'AAA',
            'score' => 2000000,
            'game' => $this->gameTwo->id,
        ]);

        $this->json('GET', 'game-scores');

        $this->assertResponseOk();

        $this->seeJson([
            'data' => [
                [
                    'type' => 'game-scores',
                    'id' => '1',
                    'attributes' => [
                        'username' => 'AAA',
                        'score' => 1000000,
                    ],
                    'relationships' => [
                        'game' => [
                            'data' => [
                                'type' => 'games',
                                'id' => (string) $this->game->id,
                            ],
                        ],
                    ],
                ],
                [
                    'type' => 'game-scores',
                    'id' => '2',
                    'attributes' => [
                        'username' => 'AAA',
                        'score' => 2000000,
                    ],
                    'relationships' => [
                        'game' => [
                            'data' => [
                                'type' => 'games',
                                'id' => (string) $this->gameTwo->id,
                            ],
                        ],
                    ],
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
            'type' => 'game-scores',
            'id' => (string) $gameScore->id,
            'attributes' => [
                'username' => 'AAA',
                'score' => 2000000,
            ],
            'relationships' => [
                'game' => [
                    'data' => [
                        'type' => 'games',
                        'id' => (string) $this->game->id,
                    ],
                ],
            ],
        ]]);

        $this->assertResponseOk();

        $this->seeJson([
            'data' => [
                'type' => 'game-scores',
                'id' => '1',
                'attributes' => [
                    'username' => 'AAA',
                    'score' => 2000000,
                ],
                'relationships' => [
                    'game' => [
                        'data' => [
                            'type' => 'games',
                            'id' => (string) $this->game->id,
                        ],
                    ],
                ],
            ],
        ]);

        $this->assertEquals(2000000, GameScore::firstOrFail()->score);
    }

    public function testGameDelete()
    {
        GameScore::create([
            'username' => 'AAA',
            'score' => 1000000,
            'game' => $this->game->id,
        ]);
        GameScore::create([
            'username' => 'AAA',
            'score' => 2000000,
            'game' => $this->gameTwo->id,
        ]);

        $this->json('DELETE', 'game-scores/1');

        $this->assertResponseStatus(204);

        $this->assertEquals(1, GameScore::count());
    }
}
