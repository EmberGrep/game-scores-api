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
            'type' => 'game',
            'attributes' => [
                'name' => $this->gameName,
            ],
        ]])->seeJson([
            'data' => [
                'type' => 'game',
                'id' => '1',
                'attributes' => [
                    'name' => $this->gameName,
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
                'type' => 'game',
                'id' => '1',
                'attributes' => [
                    'name' => $this->gameName,
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
                    'type' => 'game',
                    'id' => '1',
                    'attributes' => [
                        'name' => $this->gameName,
                    ],
                ],
                [
                    'type' => 'game',
                    'id' => '2',
                    'attributes' => [
                        'name' => $this->gameNameTwo,
                    ],
                ],
            ],
        ]);

        $this->assertEquals($this->gameName, Game::firstOrFail()->name);
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
