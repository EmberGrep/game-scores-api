<?php namespace GameScores\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use Validator;

use GameScores\Models\Game;
use GameScores\Models\GameScore;


class GameScoresController extends Controller
{
    /**
     * Game Model
     * @var GameScores\Models\GameScore
     */
    protected $gameScore;

    protected $createValidationRules = [
        'game' => 'exists:games,id',
    ];

    public function __construct(GameScore $gameScore) {
        $this->gameScore = $gameScore;
    }

    public function store(Request $req, JsonResponse $res) {
        $type = $req->json('data.type');
        $attrs = $req->json('data.attributes');

        if ($this->validateCreate($attrs)) {
            $gameScore = $this->gameScore->create($attrs);

            return new JsonResponse([
                'data' => $this->serializeGameScore($gameScore),
            ]);
        }

        return new JsonResponse([], 400);
    }

    protected function validateCreate($attrs) {
        $validator = Validator::make($attrs, $this->createValidationRules);

        if ($validator->fails()) {
            $this->errors = $validator->errors();
            return false;
        }

        return true;
    }

    protected function serializeGameScore($gameScore) {
        return [
            'type' => 'game-score',
            'id' => (string) $gameScore->id,
            'attributes' => $gameScore->toArray(),
        ];
    }
}
