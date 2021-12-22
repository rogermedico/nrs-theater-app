<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'date',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
