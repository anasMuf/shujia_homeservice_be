<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceTestimonial extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'message',
        'photo',
        'home_service_id',
    ];

    public function homeService() {
        return $this->belongsTo(HomeService::class, 'home_service_id');
    }
}
