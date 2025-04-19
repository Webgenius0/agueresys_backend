<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\God;
use App\Models\GodRole;
use App\Models\Role;
use App\Models\Vote;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{

    public function index()
    {
        try {
            $roles = Role::with([
                'godRoles' => function ($query) {
                    $query->withCount('votes');
                }
            ])
                ->get();
            foreach ($roles as $role) {
                $role->total_votes = $role->godRoles->sum('votes_count');
            }
            // dd($roles->toArray());
            return view("backend.layouts.god_role.index", compact('roles'));
        } catch (Exception $e) {
            Log::error("RoleController::index" . $e->getMessage());
            flash()->error('Something went wrong' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function show(string $id)
{
    try {
        // Fetch role info
        $role = Role::findOrFail($id);

        // Get all gods with this role (through GodRole)
        $gods = God::whereHas('godRoles', function ($query) use ($id) {
            $query->where('role_id', $id);
        })
            ->with([
                'godRoles' => function ($query) use ($id) {
                    $query->where('role_id', $id)->with('votes');
                }
            ])
            ->get();

        // Count votes per godRole and calculate net_votes
        foreach ($gods as $god) {
            foreach ($god->godRoles as $gr) {
                $gr->upvotes = $gr->votes->where('vote', 'up')->count();
                $gr->downvotes = $gr->votes->where('vote', 'down')->count();
                $gr->net_votes = $gr->upvotes - $gr->downvotes;
            }
        }

        // Sort gods by the first godRole's net_votes (assuming one role per god in this context)
        $gods = $gods->sortByDesc(function ($god) {
            return $god->godRoles->first()?->net_votes ?? 0;
        })->values(); // reset keys after sorting

        return view("backend.layouts.god_role.show", compact('role', 'gods'));

    } catch (Exception $e) {
        Log::error("RoleController::show " . $e->getMessage());
        flash()->error('Something went wrong: ' . $e->getMessage());
        return redirect()->back();
    }
}





}
