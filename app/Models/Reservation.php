<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id',
        'session_id',
        'row',
        'column',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

}
