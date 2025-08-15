<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiTrait;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
   public function __construct( protected Admin $objmodel)
    {
        
    }
    use ApiTrait;
    public function getDate()
    {
        $admins = $this->objmodel->paginate(10);
        return $this->successResponse(['date' => now(), 'admins' => $admins]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request , [
            "name" => "required|string|max:255",
            "email" => "required|email|unique:admins,email",
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
        $admin = $this->objmodel->find($id);
        if (!$admin) {
            return $this->errorResponse('admin not found', 404);
        }
        return $this->successResponse(['admin' => $admin]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $admin = $this->objmodel->find($id);
        if (!$admin) {
            return $this->errorResponse('admin not found', 404);
        }
        $admin->update($request->all());
        return $this->successResponse(['admin' => $admin]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $admin = $this->objmodel->find($id);
        if (!$admin) {
            return $this->errorResponse('admin not found', 404);
        }
        $admin->delete();
        return $this->successResponse(['message' => 'admin deleted successfully']);
    }
}
