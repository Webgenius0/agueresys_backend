<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\GodRole;
use App\Models\Vote;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'anonymous_user_id' => 'required|exists:anonymous_users,id',
            'god_role_id' => 'required|exists:god_roles,id',
        ]);

        $anonymousUserId = $request->anonymous_user_id;
        $godRole = GodRole::findOrFail($request->god_role_id);
        $godId = $godRole->god_id;

        // Count how many roles this user already voted for this god
        $voteCount = Vote::whereHas('godRole', function ($query) use ($godId) {
            $query->where('god_id', $godId);
        })->where('anonymous_user_id', $anonymousUserId)->count();

        if ($voteCount >= 2) {
            return response()->json(['message' => 'You can only vote for 2 roles per god'], 403);
        }

        // Store the vote
        Vote::create([
            'anonymous_user_id' => $anonymousUserId,
            'god_role_id' => $request->god_role_id,
        ]);

        return response()->json(['message' => 'Vote successfully recorded']);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
