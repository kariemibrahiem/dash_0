<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\UserService;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest as objRequest;

class UserController extends Controller
{
    public function __construct(protected UserService $service){}

    public function index(Request $request){
        return $this->service->index($request);
    }

    public function create(){
        return $this->service->create();
    }

    public function store(objRequest $request){
        return $this->service->store($request);
    }

    public function edit($id){
        return $this->service->edit($id);
    }

    public function update(Request $request, $id){
        return $this->service->update($request, $id);
    }

    public function destroy($id){
        return $this->service->destroy($id);
    }


    public function updateColumnSelected(Request $request)
    {
        return $this->service->updateColumnSelected($request,'status');
    }



    public function deleteSelected(Request $request){
        return $this->service->deleteSelected($request);
    }
}
