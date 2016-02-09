<?php namespace GameScores\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use GameScores\Models\GameScore;


class GameScoresController extends Controller
{
    /**
     * Game Model
     * @var GameScores\Models\GameScore
     */
    protected $gameScore;

    public function __construct(GameScore $gameScore) {
        $this->gameScore = $gameScore;
    }

    public function store(Request $req, JsonResponse $res) {
        $type = $req->json('data.type');
        $attrs = $req->json('data.attributes');

        $gameScore = $this->gameScore->create($attrs);

        return new JsonResponse([
            'data' => $this->serializeGameScore($gameScore),
        ]);
    }

    protected function serializeGameScore($gameScore) {
        return [
            'type' => 'game-score',
            'id' => (string) $gameScore->id,
            'attributes' => $gameScore->toArray(),
        ];
    }
}
