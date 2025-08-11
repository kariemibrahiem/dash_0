<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected UserService $service){}

    public function index(Request $request){
        return $this->service->index($request);
    }

    public function create(){
        return $this->service->create();
    }



    public function updateColumnSelected(Request $request)
    {
        return $this->service->updateColumnSelected($request,'status');
    }



    public function deleteSelected(Request $request){
        return $this->service->deleteSelected($request);
    }
}
