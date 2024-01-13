<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    public function return_api($isSuccess, $statusCode,  $message, $data, $error)
    {
        return response()->json([
            'is_success' => $isSuccess,
            'status_code' => $statusCode,
            'message' => $message,
            'data' => $data,
            'errors' => $error,

        ], $statusCode);
    }

    public function login(Request $request)
    {
        // Validate email and password
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // Return errors if validation failed
        if ($validator->fails()) {
            return $this->return_api(false, Response::HTTP_UNPROCESSABLE_ENTITY, null, null, $validator->errors());
        }
        $validated = $validator->validated();

        $user = User::where('email', $validated['email'])->where('password', $validated['password'])->first();

        if ($user) {
            return $this->return_api(true, Response::HTTP_OK, null, $user, null);
        }

        return $this->return_api(false, Response::HTTP_UNAUTHORIZED, "Invalid credentials", null, null);
    }

    public function createTodo(Request $request)
    {
        // Validate email and password
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string',
            'title' => 'nullable|string',
            'description' => 'required|string',
        ]);

        // Return errors if validation failed
        if ($validator->fails()) {
            return $this->return_api(false, Response::HTTP_UNPROCESSABLE_ENTITY, null, null, $validator->errors());
        }

        $validated = $validator->validated();

        $todo = Todo::create(
            [
                'user_id' => $validated['user_id'],
                'title' => $validated['title'] ?? null,
                'description' => $validated['description'],
            ]
        );
        Log::info(json_encode($todo->is_checked));

        return $this->return_api(true, Response::HTTP_OK, null, $todo, null);
    }
    public function readTodo()
    {
    }
    public function updateTodo()
    {
    }
    public function deleteTodo()
    {
    }

    public function register(Request $request)
    {
        // Validate email and password
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        // Return errors if validation failed
        if ($validator->fails()) {
            return $this->return_api(false, Response::HTTP_UNPROCESSABLE_ENTITY, null, null, $validator->errors());
        }

        $validated = $validator->validated();

        try {
            $user =  User::create(
                [
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => $validated['password'],
                ]
            );
        } catch (\Throwable $th) {
            return $this->return_api(true, Response::HTTP_CONFLICT, "Email already exist", null, null);
        }

        return $this->return_api(true, Response::HTTP_OK, "New user registered", null, null);
    }
}
