<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use CrudTrait;
    use HasFactory;
    protected $fillable = [
        'service_provider_id',
        'date'
    ];

    public function serviceProvider()
    {
        return $this->belongsTo(User::class, 'service_provider_id')
                ->whereHas('roles', function ($query) {
                    $query->where('role_id', 4); // Adjust the role ID as needed
                });
    }
}
