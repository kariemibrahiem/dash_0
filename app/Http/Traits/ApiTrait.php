<?php 

namespace App\Http\Traits;

trait ApiTrait
{
   
    public function successResponse($data = [], $message = 'Success')
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], 200);
    }

  
    public function errorResponse($data = [] , $message = 'Error', $code = 400)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}