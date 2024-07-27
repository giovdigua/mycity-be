<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\UserResource;
use App\Models\User;
use Gate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{

    public function index(): JsonResponse
    {
        $users = User::query()->orderBy('created_at','desc')->get();

        return $this->sendResponse(['users' => UserResource::collection($users)], 'All users retrieved successfully.');
    }

    public function destroy(int $userId): JsonResponse
    {
        $authUser = Auth::user();
        $user = User::findOrFail($userId);
        if(!$user){
            return $this->sendError('User not found.');
        }
        if ($authUser->cannot('delete', $user)) {
            return $this->sendError("You are not authorized to delete users",[],403);
        }

        $user->delete();

        return $this->sendResponse('deleted', 'User deleted successfully.');
    }

    public function update(Request $request, int $userId):JsonResponse
    {
        $authUser = Auth::user();
        $user = User::findOrFail($userId);
        if(!$user){
            return $this->sendError('User not found.');
        }
        if ($authUser->cannot('update', $user)) {
            return $this->sendError("You are not authorized to update users",[],403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,',id',
            'phone_number' => ['required','string', 'regex:/^[\d+]+$/'],
            'fiscal_code' => ['required', 'string', 'size:16', 'regex:/^[a-zA-Z]{6}[0-9]{2}[a-zA-Z]{1}[0-9]{2}[a-zA-Z]{1}[0-9]{3}[a-zA-Z]{1}$/i'],
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', ['error' => $validator->errors()]);
        }

        $input = $request->all();
        $input['fiscal_code'] = strtoupper($input['fiscal_code']);
        $user->update($input);
        return $this->sendResponse(UserResource::make($user), 'User updated');
    }
}
