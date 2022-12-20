<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public function scopeColumns($query, $columns=[])
    {
        if(count($columns) > 0){
            return $query->select($columns);
        }
        return $query->select([
            'users.id', 'name', 'user_id', 'description', 'status'
        ]);
    }

    public function scopeRelations($query, ...$relations)
    {
        if(count($relations) > 0) {
            foreach ($relations as $relation) {
                if ($relation === 'user') {
                    $query->with(['user' => function($q) {
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
