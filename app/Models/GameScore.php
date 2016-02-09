<?php namespace GameScores\Models;

use Illuminate\Database\Eloquent\Model;

class GameScore extends Model
{
    protected $table = 'game_scores';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'score',
        'game',
    ];

    protected $appends = [
        'game',
    ];

    protected $hidden = [
        'id',
        'game_id',
        'updated_at',
        'created_at',
    ];

    public function game() {
        return $this->belongsTo(Game::class);
    }

    public function setGameAttribute($value) {
        $this->attributes['game_id'] = $value;
    }

    public function getGameAttribute() {
        return $this->attributes['game_id'];
    }
}
