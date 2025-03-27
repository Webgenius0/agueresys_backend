<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\AnonymousUser;
use App\Models\GodRole;
use App\Models\Vote;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $validatedData = $request->validate([
            // 'anonymous_user_id' => 'required|exists:anonymous_users,id',
            'god_role_id' => 'required|exists:god_roles,id',
            'vote' => 'required|in:up,down',
        ]);

        try {
            DB::beginTransaction();
            // Retrieve fingerprint from request headers
            $fingerprint = $request->header('Fingerprint');
            $ipAddress = $request->ip();

            if (!$fingerprint) {
                return response()->json(['message' => 'Fingerprint header missing'], 400);
            }

            $fingerprint = $request->header('Fingerprint');
            $anonymousUser = AnonymousUser::where('fingerprint', $fingerprint)->first();
            // Check if the anonymous user exists
            if (!$anonymousUser) {
                AnonymousUser::create([
                    'ip_address' => $ipAddress . 15,
                    'fingerprint' => $fingerprint
                ]);
                $anonymousUser = AnonymousUser::where('fingerprint', $fingerprint)->first();
            }

            $anonymousUserId = $anonymousUser->id;
            $godRole = GodRole::findOrFail($request->god_role_id);
            $godId = $godRole->god_id;
            //if exits vote
            $vote = Vote::where('anonymous_user_id', $anonymousUserId)->where('god_role_id', $request->god_role_id)->first();
            if ($vote) {
                // dd($vote->toArray());
                if ($vote->vote === $validatedData['vote']) {
                    // dd('old vote');
                    $vote->delete();
                    DB::commit();
                    return Helper::jsonErrorResponse('Your vote has been changed and deleted', 403);
                } else {
                    // dd('change vote');
                    $vote->update(['vote' => $validatedData['vote']]);
                    Log::info('Vote updated successfully' . $vote);
                    DB::commit();
                    return Helper::jsonResponse(true, 'Vote successfully recorded', 200);
                }
            } else {
                // Count how many roles this user already voted for this god
                $voteCount = Vote::whereHas('godRole', function ($query) use ($godId) {
                    $query->where('god_id', $godId);
                })->where('anonymous_user_id', $anonymousUserId)->count();

                if ($voteCount >= 2) {
                    return Helper::jsonErrorResponse('You can only vote for 2 roles per god', 403);
                }

                // Store the vote
                Vote::create([
                    'anonymous_user_id' => $anonymousUserId,
                    'god_role_id' => $request->god_role_id,
                    'vote' => $validatedData['vote']
                ]);
            }
            Log::info('last vote' . $vote);

            DB::commit();
            return Helper::jsonResponse(true, 'Vote successfully recorded', 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("VoteController::store" . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to record vote' . $e->getMessage(), 500);
        }
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
