<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    protected $gameName = 'Donkey Kong Country';

    use DatabaseMigrations, DatabaseTransactions;

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->post('/game', ['data' => [
            'type' => 'game',
            'attributes' => [
                'name' => $this->gameName,
            ],
        ]]);

        // ->seeJson(['data' => [
        //     'type' => 'game',
        //     'id' => '1',
        //     'attributes' => [
        //         'name' => $this->gameName,
        //     ]
        // ]]);

        $this->assertEquals($this->gameName, Game::first()->name);
    }
}
