<?php namespace GameScores\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $table = 'games';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];


    protected $hidden = [
        'id',
        'updated_at',
        'created_at',
    ];
}
