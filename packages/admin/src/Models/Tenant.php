<?php
// app/Models/Tenant.php

namespace Payflow\Admin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'domain',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
