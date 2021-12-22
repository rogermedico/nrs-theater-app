<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'surname',
        'id_session',
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
