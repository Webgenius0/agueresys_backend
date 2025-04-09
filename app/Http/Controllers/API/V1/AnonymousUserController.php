<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\AnonymousUser;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use function Laravel\Prompts\select;

class AnonymousUserController extends Controller
{

    /**
     * Get all anonymous users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $per_page = $request->has('per_page') ? $request->per_page : 25;
            $anonymousUsers = AnonymousUser::paginate($per_page);
            return Helper::jsonResponse(true, 'Anonymous users retrieved successfully', 200, $anonymousUsers, true);
        } catch (Exception $e) {
            Log::error("AnonymousUserController::index" . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to retrieve users', 403);
        }
    }


    /**
     * Create a new anonymous user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'ip_address' => 'nullable|ip',
            // 'ip_address' => 'nullable|ip|unique:anonymous_users',
            'fingerprint' => 'required|string|unique:anonymous_users,fingerprint',
        ]);
        try {
            $anonymousUser = AnonymousUser::create($validatedData);
            return Helper::jsonResponse(true, 'Anonymous user created successfully', 201, $anonymousUser);
        } catch (Exception $e) {
            Log::error("AnonymousUserController::store" . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to create user');
        }
    }


    /**
     * Display the specified resource.
     *
     * @param string $fingerprint The fingerprint of the user to retrieve
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($fingerprint): JsonResponse
    {
        try {
            $anonymousUser = AnonymousUser::where('fingerprint', $fingerprint)->firstOrFail();
            return Helper::jsonResponse(true, 'User retrieved successfully', 200, $anonymousUser);
        } catch (Exception $e) {
            Log::error("AnonymousUserController::show" . $e->getMessage());
            return Helper::jsonErrorResponse('User not found' . $e->getMessage(), 403);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validatedData = $request->validate([
            'ip_address' => 'nullable|ip|unique:anonymous_users,ip_address,' . $id,
            'fingerprint' => 'nullable|string|unique:anonymous_users,fingerprint,' . $id,
        ]);
        try {
            $anonymousUser = AnonymousUser::findOrFail($id);
            $anonymousUser->update($validatedData);
            return Helper::jsonResponse(true, 'User updated successfully', 201, $anonymousUser);
        } catch (Exception $e) {
            return Helper::jsonErrorResponse('Failed to update user' . $e->getMessage(), 403);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id The fingerprint of the user to delete
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $anonymousUser = AnonymousUser::findOrFail($id);
            $anonymousUser->delete();
            return Helper::jsonResponse(true, 'User deleted successfully', 200);
        } catch (Exception $e) {
            return Helper::jsonErrorResponse('Failed to delete user' . $e->getMessage(), 403);
        }
    }
}
