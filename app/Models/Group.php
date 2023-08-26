<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'expire_hours',
    ];

    /**
     * Пользователи который входит в группу
     */
    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'group_user',
            'group_id',
            'user_id',
        );
    }
}
