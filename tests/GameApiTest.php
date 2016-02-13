<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use GameScores\Models\Game;

class GameApiTest extends TestCase
{
    protected $gameName = 'Donkey Kong Country';
    protected $gameNameTwo = 'Mario Kart';

    use DatabaseMigrations, DatabaseTransactions;

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testCreateGame()
    {
        $this->json('POST', 'games', ['data' => [
            'type' => 'games',
            'attributes' => [
                'name' => $this->gameName,
            ],
            'relationships' => [
                'scores' => []
            ],
        ]])->seeJson([
            'data' => [
                'type' => 'games',
                'id' => '1',
                'attributes' => [
                    'name' => $this->gameName,
                ],
                'relationships' => [
                    'scores' => []
                ],
            ],
        ]);

        $this->assertEquals($this->gameName, Game::firstOrFail()->name);
    }

    public function testGetGame()
    {
        Game::create(['name' => $this->gameName]);

        $this->json('GET', 'games/1');

        $this->assertResponseOk();

        $this->seeJson([
            'data' => [
                'type' => 'games',
                'id' => '1',
                'attributes' => [
                    'name' => $this->gameName,
                ],
                'relationships' => [
                    'scores' => []
                ],
            ],
        ]);

        $this->assertEquals($this->gameName, Game::firstOrFail()->name);
    }

    public function testGameIndex()
    {
        Game::create(['name' => $this->gameName]);
        Game::create(['name' => $this->gameNameTwo]);

        $this->json('GET', 'games');

        $this->assertResponseOk();

        $this->seeJson([
            'data' => [
                [
                    'type' => 'games',
                    'id' => '1',
                    'attributes' => [
                        'name' => $this->gameName,
                    ],
                    'relationships' => [
                        'scores' => []
                    ],
                ],
                [
                    'type' => 'games',
                    'id' => '2',
                    'attributes' => [
                        'name' => $this->gameNameTwo,
                    ],
                    'relationships' => [
                        'scores' => []
                    ],
                ],
            ],
        ]);

        $this->assertEquals($this->gameName, Game::firstOrFail()->name);
    }

    public function testGameUpdate()
    {
        $game = Game::create(['name' => $this->gameName]);

        $this->json('PUT', "games/{$game->id}", ['data' => [
            'type' => 'games',
            'id' => $game->id,
            'attributes' => [
                'name' => $this->gameNameTwo,
            ],
            'relationships' => [
                'scores' => []
            ],
        ]]);

        $this->assertResponseOk();

        $this->seeJson([
            'data' => [
                'type' => 'games',
                'id' => '1',
                'attributes' => [
                    'name' => $this->gameNameTwo,
                ],
                'relationships' => [
                    'scores' => []
                ],
            ],
        ]);

        $this->assertEquals($this->gameNameTwo, Game::firstOrFail()->name, 'Game updates should be saved to DB');
    }

    public function testGameDelete()
    {
        Game::create(['name' => $this->gameName]);
        Game::create(['name' => $this->gameNameTwo]);

        $this->json('DELETE', 'games/1');

        $this->assertResponseStatus(204);

        $this->assertEquals(1, Game::count());
    }
}
