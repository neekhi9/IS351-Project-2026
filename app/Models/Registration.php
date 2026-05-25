<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_type', 'organization_name', 'organization_type', 
        'designation_business', 'com_reg_num', 'tin_number',
        'title', 'first_name', 'surname', 'address', 'street', 
        'suburb', 'city_id', 'region_id', 'office_phone', 
        'mobile_phone', 'email', 'alt_email', 'roc_file_path', 
        'tin_letter_path', 'wireman_l_num_ind', 'wireman_license_ind_path'
    ];

    protected $casts = [
        'invalid_fields' => 'array',
        'reviewed_at' => 'datetime',
        'resubmission_expires_at' => 'datetime',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function directors()
    {
        return $this->hasMany(Director::class);
    }

    public function wiremanLicenses()
    {
        return $this->hasMany(WiremanLicense::class);
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
