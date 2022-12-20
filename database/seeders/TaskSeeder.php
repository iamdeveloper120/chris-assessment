<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $tasks = array();
        $tasks[] = [
            'name' => 'Muhammad',
            'owner' => '1',
            'description' => 'First task seed',
            'status' => 'in_progress'
        ];
        $tasks[] = [
            'name' => 'chris',
            'owner' => '1',
            'description' => 'Second task seed',
            'status' => 'in_progress'
        ];
        foreach ($tasks as $task) {
            $taskRecord = [
                'name' =>  $task['name'],
                'user_id' =>  $task['owner'],
                'description' => $task['description'],
                'status' => $task['status'],
            ];
            Task::updateOrCreate([
                'email' => $task['email']
            ], $taskRecord);
        }
    }
}
