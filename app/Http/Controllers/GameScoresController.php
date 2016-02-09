<?php namespace GameScores\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use Validator;

use GameScores\Models\GameScore;


class GameScoresController extends Controller
{
    /**
     * Game Model
     * @var GameScores\Models\GameScore
     */
    protected $gameScore;

    protected $rules = [
        'game' => 'exists:games,id',
        'username' => 'required|min:2',
        'score' => 'numeric|min:0',
    ];

    public function __construct(GameScore $gameScore) {
        $this->gameScore = $gameScore;
    }

    public function index(JsonResponse $res) {
        $controller = $this;
        $gameScores = $this->gameScore->orderBy('id', 'asc')->get();

        return new JsonResponse([
            'data' => $gameScores->map(function($gameScore) use ($controller) {
                return $controller->serializeGameScore($gameScore);
            }),
        ]);
    }

    public function find(JsonResponse $res, $id) {
        $gameScore = $this->gameScore->findOrFail($id);

        return new JsonResponse([
            'data' => $this->serializeGameScore($gameScore),
        ]);
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

        return new JsonResponse([
            'errors' => array_map(function ($err) {
                return [
                    'status' => '400',
                    'title' => 'Invalid Attribute',
                    'detail' => $err,
                ];
            }, $this->errors->all()),
        ], 400);
    }

    public function update(Request $req, JsonResponse $res, $id) {
        $type = $req->json('data.type');
        $attrs = $req->json('data.attributes');

        $game = $this->gameScore->find($id);
        $game->fill($attrs);
        $game->save();

        return new JsonResponse([
            'data' => $this->serializeGameScore($game),
        ]);
    }

    protected function validateCreate($attrs) {
        $validator = Validator::make($attrs, $this->rules);

        if ($validator->fails()) {
            $this->errors = $validator->errors();
            return false;
        }

        return true;
    }

    public function delete(JsonResponse $res, $id) {
        $this->gameScore->destroy($id);

        return new JsonResponse(null, 204);
    }

    protected function serializeGameScore($gameScore) {
        return [
            'type' => 'game-score',
            'id' => (string) $gameScore->id,
            'attributes' => $gameScore->toArray(),
        ];
    }
}
