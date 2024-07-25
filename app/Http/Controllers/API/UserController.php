<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BaseController
{

    public function index(Request $request): JsonResponse
    {
        if ($request->user()->cannot('viewAny', User::class)) {
            abort(403);
        }
        $users = User::all();

        return $this->sendResponse(['users' => UserResource::collection($users)], 'All users retrieved successfully.');

    }

    public function destroy(Request $request,User $user): JsonResponse
    {
        if ($request->user()->cannot('delete', User::class)) {
            abort(403);
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
