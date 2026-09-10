<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Role extends Model
{
    use SoftDeletes;

    protected $table = 'roles';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];
    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }
    public function priviledge()
    {
        return $this->hasMany(Priviledge::class, 'role_id', 'id');
    }
}