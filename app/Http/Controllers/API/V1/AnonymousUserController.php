<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\AnonymousUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use function Laravel\Prompts\select;

class AnonymousUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
     */
    public function show($fingerprint)
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
     */
    public function update(Request $request, $id)
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
     */
    public function destroy($id)
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
