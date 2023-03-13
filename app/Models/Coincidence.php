<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coincidence extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'match_percentage',
        'department',
        'location',
        'town',
        'active_years',
        'person_type',
        'position_type',
        'match_search_id'
    ];

}
