<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiTrait;
use App\Models\User;
use App\Models\UserMail;
use App\Traits\FirebaseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    use ApiTrait , FirebaseNotification;

    protected User $user;
    protected UserMail $userMail;

    public function __construct(User $user, UserMail $userMail)
    {
        $this->user = $user;
        $this->userMail = $userMail;
    }

    /**
     * Display a listing of the users.
     */


    public function getDate()
    {
        $user = $this->user->where("id" , auth("user-api")->id())->with('UserMail')->get();

        return $this->successResponse([
            'user' => $user
        ]);
    }

    /**
     * Store a newly created user in storage.
     */
    
    /**
     * Display the specified user.
     */
    public function show($id)
    {
        $user = $this->user->with('UserMail')->find($id);

        if (!$user) {
            return $this->errorResponse('User not found', 404);
        }

        return $this->successResponse(['user' => $user]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $user = $this->user->findOrFail($id);

            $request->validate([
                "name" => "sometimes|string|max:255",
                "email" => "sometimes|email|unique:users,email," . $user->id,
                "password" => "nullable|string|min:6|confirmed",
                "image" => "nullable|image|max:2048",
            ]);

            // Handle image
            $path = $user->image;
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store("users", "public");
            }

            // Update UserMail
            if ($request->has("email")) {
                $user->userMail()->update([
                    "email" => $request->email,
                ]);
            }

            // Update user
            $user->update([
                "name" => $request->name ?? $user->name,
                "password" => $request->password ? Hash::make($request->password) : $user->password,
                "code" => $request->code ?? $user->code,
                "email" => $request->email ?? $user->email,
                "status" => $request->status ?? $user->status,
                "image" => $path,
            ]);

            return $this->successResponse(['user' => $user], "User updated successfully");
        } catch (Exception $e) {
            return $this->errorResponse("Update failed: " . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        try {
            $user = $this->user->findOrFail($id);
            $user->delete();

            return $this->successResponse([], "User deleted successfully");
        } catch (Exception $e) {
            return $this->errorResponse("Delete failed: " . $e->getMessage(), 500);
        }
    }
}
