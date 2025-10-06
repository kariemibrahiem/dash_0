<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Services\Admin\RoleService as objService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(protected  objService $ObjService) {
        
    }
    public function index(Request $request)
    {
        return $this->ObjService->index($request);
    }

    public function create()
    {
        return $this->ObjService->create();
    }

    public function store(Request $request)
    {
        return $this->ObjService->store($request->all());
    }

    public function edit($id)
    {
        return $this->ObjService->edit($id);
    }

    public function update(Request $request, $id)
    {
        return $this->ObjService->update($request->all(), $id);
    }

    public function destroy($id)
    {

        return $this->ObjService->destroy($id);
    }
}
