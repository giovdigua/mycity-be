<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\UserResource;
use App\Models\User;
use Gate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BaseController
{


    public function index(): JsonResponse
    {
        $users = User::all();

        return $this->sendResponse(['users' => UserResource::collection($users)], 'All users retrieved successfully.');

    }

    public function destroy(int $userId): JsonResponse
    {
        $user = User::findOrFail($userId);
        if(!$user){
            return $this->sendError('User not found.');
        }
        $user->delete();

        return $this->sendResponse('deleted', 'User deleted successfully.');
    }

    public function update(Request $request, User $user):JsonResponse
    {
        if ($request->user()->cannot('update', User::class)) {
            abort(403);
        }
        $user->update($request->all());
        return $this->sendResponse(UserResource::make($user), 'User updated');
    }
}
