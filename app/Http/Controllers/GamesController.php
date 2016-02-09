<?php namespace GameScores\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use GameScores\Models\Game;


class GamesController extends Controller
{
    /**
     * Game Model
     * @var GameScores\Models\Game
     */
    protected $game;

    public function __construct(Game $game) {
        $this->game = $game;
    }

    public function store(Request $req, JsonResponse $res) {
        $type = $req->json('data.type');
        $attrs = $req->json('data.attributes');

        $game = $this->game->create($attrs);

        return new JsonResponse([
            'data' => [
                'type' => 'game',
                'id' => $game->id,
                'attributes' => $game->toArray(),
            ]
        ]);
    }
}
