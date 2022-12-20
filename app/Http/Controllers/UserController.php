<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    use ResponseTrait;

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $user = User::with([
            'tasks' => function($q) {
                $q->columns()->orderBy('id','desc');
            },
        ])->select(['id', 'first_name','last_name','email']);

        $this->data = $user->get();
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
        $userModel = new User();
        if(isset($data['id']) || $id) {
            try {
                $userModel = User::findOrFail($id);
            } catch (ModelNotFoundException $e) {
                $this->success = false;
                $this->status = 404;
                $this->errors[] = $this->message = $e->getMessage();
                return $this->apiResponse();
            }
        }
        if (isset($data['first_name'])) {
            $userModel->first_name = $data['first_name'];
        }
        if (isset($data['last_name'])) {
            $userModel->last_name = $data['last_name'];
        }
        if (isset($data['email'])) {
            $userModel->email = $data['email'];
        }

        try {
            $userModel->save();
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
        $user = User::with([
            'tasks' => function($q) {
                $q->columns()->orderBy('id','desc');
            },
        ])->select(['id', 'first_name','last_name','email']);

        $this->data = $user->first();
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
            $user = User::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $this->success = false;
            $this->status = 404;
            $this->errors[] = $this->message = $e->getMessage();
            return $this->apiResponse();
        }
        $user->delete();
        $this->message = 'User deleted successfully';
        return $this->apiResponse();
    }
}
