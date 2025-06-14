<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $primaryKey = 'store_id';

    protected $fillable = [
        'name',
        'address',
        'phone',
        'manager_id',
        'image',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function bikes()
    {
        return $this->hasMany(Bike::class);
    }
}
