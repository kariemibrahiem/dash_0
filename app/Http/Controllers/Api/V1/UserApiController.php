<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest as ObjRequest;
use App\Models\User as ObjModel;
use Illuminate\Http\Request;

class UserApiController extends Controller
{
    public function __construct(protected ObjModel $objModel){}
    public function getData()
    {
        try{
            $data = $this->objModel->paginate();
            return $this->successResponse($data, 200, "تمت العملية بنجاح");
        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(), 500, "حدث خطأ ما.");
        }
    }
    public function getById($id)
    {
        try{
            $data = $this->objModel->findOrFail($id);
            return $this->successResponse($data, 200, "تمت العملية بنجاح");
        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(), 500, "حدث خطأ ما.");
        }
    }

    public function store(ObjRequest $request)
    {
        try{
            $data = $request->validated();
            if (isset($data['file'])) {
                $data['file'] = $this->handleFile($data['file'], 'User');
            }
            $obj = $this->objModel->create($data);
            return $this->successResponse($obj, 201, "تمت العملية بنجاح");
        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(), 500, "حدث خطأ ما.");
        }
    }

    public function update(ObjRequest $request, $id)
    {
        try{
            $data = $request->validated();
            $obj = $this->objModel->findOrFail($id);
            if (isset($data['file'])) {
                $data['file'] = $this->handleFile($data['file'], 'User');
                if ($obj->file) {
                    $this->deleteFile($obj->file);
                }
            }
            $obj->update($data);
            return $this->successResponse($obj, 200, "تمت العملية بنجاح");
        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(), 500, "حدث خطأ ما.");
        }
    }

    public function destroy($id)
    {
        try{
            $obj = $this->objModel->findOrFail($id);
            $obj->delete();
            return $this->successResponse([], 204, "تمت العملية بنجاح");
        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(), 500, "حدث خطأ ما.");
        }
    }

    private function successResponse($data =[] , $status = 200, $message = "تمت العملية بنجاح"){
        return response()->json(['status' => $status, 'message' => $message, 'data' => $data]);
    }

    private function errorResponse($message = "حدث خطأ ما.", $status = 500, $error = null){
        return response()->json(['status' => $status, 'message' => $message, 'error' => $error]);
    }

}