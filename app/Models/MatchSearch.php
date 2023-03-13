<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchSearch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'match_percentage',
        'execution_state'
    ];

    public function coincidences()
    {
        return $this->hasMany(Coincidence::class);
    }

}
