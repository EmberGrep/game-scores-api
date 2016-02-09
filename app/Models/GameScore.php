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
        'game_id',
    ];


    protected $hidden = [
        'id',
        'game',
        'updated_at',
        'created_at',
    ];
}
