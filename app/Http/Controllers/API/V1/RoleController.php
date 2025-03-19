<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{

    /**
     * Get all roles
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $per_page = $request->has('per_page') ? $request->per_page : 25;
            $roles = Role::where('status', 'active')->paginate($per_page);
            return Helper::jsonResponse(true, 'Roles retrieved successfully.', 200, $roles, true);
        } catch (Exception $e) {
            Log::error("RoleController::index" . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to retrieve roles', 403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $role = Role::where('status', 'active')->where('id', $id)->firstOrFail();
            return Helper::jsonResponse(true, 'Role retrieved successfully.', 200, $role);
        } catch (Exception $e) {
            Log::error("RoleController::show" . $e->getMessage());
            return Helper::jsonErrorResponse('Role not found', 403);
        }
    }

}
