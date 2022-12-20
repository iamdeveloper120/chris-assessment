<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeColumns($query, $columns=[])
    {
        if(count($columns) > 0){
            return $query->select($columns);
        }
        return $query->select([
            'users.id', 'first_name', 'last_name', 'email'
        ]);
    }

    public function scopeRelations($query, ...$relations)
    {
        if(count($relations) > 0) {
            foreach ($relations as $relation) {
                if ($relation === 'tasks') {
                    $query->with(['tasks' => function($q) {
                        $q->columns();
                    }]);
                }
            }
            return $query;
        }

        return $query->with([
            'tasks' => function($q) {
                $q->columns();
            }
        ]);
    }
}
