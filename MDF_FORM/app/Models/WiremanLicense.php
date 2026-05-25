<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WiremanLicense extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['registration_id', 'license_number', 'license_file_path'];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
