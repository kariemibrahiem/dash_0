<?php
namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiTrait;
use App\Models\coupon;
use App\Models\Email;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    use ApiTrait;
    
    public function __construct(protected User $objmodel , protected Email $emailModel)
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
    
    public function regist(Request $request)
    {
        try{
            $this->validate($request , [
                "email" => "required|email|unique:users,email"
            ]);
        
            $user = $this->objmodel->where('email', $request->email)->first();
            
            if($user){
                return $this->errorResponse('User already exists', 409);
            }

        $otp = rand(100000, 999999);
            $this->emailModel->updateOrCreate(
                ["email" => $request->email],
                ["otp" => $otp, "verified_at" => null , "exp_date" => now()->addMinutes(10)]
            );


            Mail::send("content.mails.forgotPassword", ["otp" => $otp], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('regist to our platform');
            });

            return $this->successResponse([
                "email" => $request->email,
            ], 'otp send successfully');
        }catch(\Exception $e){
            return $this->errorResponse('Unable to send OTP email. Please try again later.', 500);
        }
    }

    public function otpCheck(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'otp' => 'required|integer',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            "coupon" => "nullable|exists:coupons,code"
        ]);

        $mailRecord = $this->emailModel->where('email', $request->email)->first();

        if (!$mailRecord) {
            return $this->errorResponse('Email not found', 404);
        }

        try {
            DB::beginTransaction();

            if (!($mailRecord->otp && (int)$mailRecord->otp === (int)$request->otp && $mailRecord->exp_date >= now())) {
                DB::rollBack();
                return $this->errorResponse('Invalid or expired OTP', 401);
            }

            $mailRecord->verified_at = now();
            $mailRecord->otp = null;
            $mailRecord->save();

            $user = $this->objmodel->create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => bcrypt($request->password),
                "email_id" => $mailRecord->id
            ]);

            if ($request->filled("coupon")) {
                $coupon = Coupon::where("code", $request->coupon)->lockForUpdate()->first();

                if (!$coupon) {
                    throw new \Exception('Coupon not found', 404);
                }
                if ($coupon->exp_date <= now()) {
                    throw new \Exception('Coupon has expired', 410);
                }
                if ($coupon->usage_count >= $coupon->accept_usage) {
                    throw new \Exception('Coupon usage limit reached', 429);
                }
                if ($user->coupon_id) {
                    throw new \Exception('User already has a coupon', 409);
                }

                $user->coupon_id = $coupon->id;
                $user->save();

                $coupon->increment('usage_count');
            }

            DB::commit();

            $token = $user->createToken('API Token')->plainTextToken;

            return $this->successResponse([
                "user" => $user,
                "token" => $token
            ], 'OTP is valid');

        } catch (\Exception $e) {
            DB::rollBack();

            $code = in_array($e->getCode(), [404, 410, 429, 409]) ? $e->getCode() : 500;
            $message = $code === 500 ? 'An error occurred while processing your request' : $e->getMessage();

            return $this->errorResponse($message, $code);
        }
    }



    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->successResponse([], 'Logout successful');
    }
}
