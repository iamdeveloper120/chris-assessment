<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use ResponseTrait;

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $tasks = Task::with([
            'user' => function($q) {
                $q->columns();
            },
        ])->select(['id', 'name','owner','description', 'status']);

        $this->data = $tasks->get();

        return $this->apiResponse();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param null $id
     * @return JsonResponse
     */
    public function store(Request $request, $id = null): JsonResponse
    {
        $taskModel = new Task();
        if($id) {
            try {
                $taskModel = Task::findOrFail($id);
            } catch (ModelNotFoundException $e) {
                $this->success = false;
                $this->status = 404;
                $this->errors[] = $this->message = $e->getMessage();
                return $this->apiResponse();
            }
        }
        if (isset($data['name'])) {
            $taskModel->name = $data['name'];
        }
        if (isset($data['user_id'])) {
            $taskModel->user_id = $data['user_id'];
        }
        if (isset($data['description'])) {
            $taskModel->description = $data['description'];
        }

        try {
            $taskModel->save();
            $this->message = __("Record has been created successfully");
            $this->status = 201;
            return $this->apiResponse();
        } catch (ModelNotFoundException $e) {
            $this->success = false;
            $this->status = 422;
            $this->errors[] = $this->message = $e->getMessage();
            return $this->apiResponse();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $taskModel = Task::with([
            'user' => function($q) {
                $q->columns();
            },
        ])->select(['id', 'name','owner','description', 'status']);

        $this->data = $taskModel->first();
        return $this->apiResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $taskModel = Task::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $this->success = false;
            $this->status = 404;
            $this->errors[] = $this->message = $e->getMessage();
            return $this->apiResponse();
        }
        $taskModel->delete();
        $this->message = __("Task deleted successfully");
        return $this->apiResponse();
    }
}
