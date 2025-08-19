<?php
namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiTrait;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiTrait;
    
    public function __construct(protected User $objmodel)
    {
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (auth()->attempt($request->only('email', 'password'))) {
            $token = auth()->user()->createToken('API Token')->plainTextToken;
            return $this->successResponse(['token' => $token], 'Login successful');
        }

        return $this->errorResponse('Invalid credentials', 401);
    }
    
    public function register(Request $request)
    {
        $this->validate($request , [
            "name" => "required|string|max:255",
            "email" => "required|email|unique:users,email",
            "password" => "required|string|min:6|confirmed",
        ]);

        $user = $this->objmodel->create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password),
        ]);

        $token = $user->createToken('API Token')->plainTextToken;

        return $this->successResponse([
            'user' => $user,
            'token' => $token
        ], 'Registration successful');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->invalidate();
        return $this->successResponse([], 'Logout successful');
    }
}
