<?php

namespace App\Http\Controllers\API;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => ['required','string', 'regex:/^[\d+]+$/'],
            'fiscal_code' => ['required', 'string', 'size:16', 'regex:/^[a-zA-Z]{6}[0-9]{2}[a-zA-Z]{1}[0-9]{2}[a-zA-Z]{1}[0-9]{3}[a-zA-Z]{1}$/i'],
            'password' => 'required|string|min:8',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', ['error' => $validator->errors()]);
        }

        $input = $request->all();
        $input['fiscal_code'] = strtoupper($input['fiscal_code']);
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['name'] =  $user->name;
        event(new Registered($user));

        return $this->sendResponse($success, 'Please verify your email address.');
    }

    /**
     * Login api
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            if (!$user->hasVerifiedEmail()) {
                return $this->sendError('Unauthorised.', ['error'=>'User without confirmation email'], 403);
            }
            $token =  $user->createToken('MyCity')->accessToken;
            $success['user'] =  $user;
            $success['token'] =  $token;

            return $this->sendResponse($success, 'User login successfully.');
        }
        else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised.User Not Found or Bad Credentials Insert']);
        }
    }
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return $this->sendResponse(['message' => 'Logged out'],'Logout', 200);
    }

    public function verify(Request $request): Application|RedirectResponse|Redirector|JsonResponse
    {
        $user = User::find($request->id);

        if ($user->hasVerifiedEmail()) {
            return redirect('/email/already-verified');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }
        return redirect()->away('http://localhost:8080/login?verfiedemail=success');
    }

    public function send(Request $request): JsonResponse
    {    $user = User::where('email',$request->email)->first();
        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 200);
        }

        $user->sendEmailVerificationNotification();
        return response()->json(['message' => 'Verification email resent'], 200);
    }
}
