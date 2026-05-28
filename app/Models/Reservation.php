<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'party_size',
        'reservation_time',
        'status',
        'special_request',
    ];

    protected function casts(): array
    {
        return [
            'reservation_time' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
