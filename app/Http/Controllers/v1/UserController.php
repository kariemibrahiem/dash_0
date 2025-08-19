<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiTrait;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct( protected User $objmodel)
    {
        
    }
    use ApiTrait;
    public function getDate()
    {
        $users = $this->objmodel->paginate(10);
        return $this->successResponse(['date' => now(), 'users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request , [
            "name" => "required|string|max:255",
            "email" => "required|email|unique:users,email",
            "password" => "required|string|min:6|confirmed",
        ]);
        $user = $this->objmodel->create($request->all());
        return $this->successResponse(['user' => $user]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = $this->objmodel->find($id);
        if (!$user) {
            return $this->errorResponse('User not found', 404);
        }
        return $this->successResponse(['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = $this->objmodel->find($id);
        if (!$user) {
            return $this->errorResponse('User not found', 404);
        }
        $user->update($request->all());
        return $this->successResponse(['user' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = $this->objmodel->find($id);
        if (!$user) {
            return $this->errorResponse('User not found', 404);
        }
        $user->delete();
        return $this->successResponse(['message' => 'User deleted successfully']);
    }
}
