<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\GodsResource;
use App\Models\AnonymousUser;
use App\Models\God;
use App\Models\GodRole;
use App\Models\GodsCounter;
use App\Models\Vote;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;

class GodsCounterController extends Controller
{

    public function getGodsCounters(Request $request, string $godSlug)
    {
        try {
            $per_page = $request->has('per_page') ? $request->per_page : 25;
            $fingerprint = $request->header('Fingerprint');
            $anonymousUser = AnonymousUser::where('fingerprint', $fingerprint)->first();
            // dd($godSlug);
            $god = God::where('slug', $godSlug)->first();
            // dd($god);
            $gods = God::withCount([
                'counters as upvotes_count' => function ($query) use ($god) {
                    $query->where('god_id', $god->id)->where('counter_god_id', '!=', $god->id )->where('vote', 'up');
                },
                'counters as downvotes_count' => function ($query) use ($god) {
                    $query->where('god_id', $god->id)->where('counter_god_id', '!=', $god->id )->where('vote', 'down');
                }
            ])
                ->where('status', 'active')
                ->where('id', '!=', $god->id)
                ->get()
                ->map(function ($gods) use ($anonymousUser, $god) {
                    // Check if the current user has voted for this god
                    $userVote = $gods->counters->where('anonymous_user_id', $anonymousUser->id)->where('god_id', $god->id)->first();
                    // Add is_vote and vote_value to each god
                    $gods->is_vote = $userVote ? true : false;
                    $gods->vote_value = $userVote ? $userVote->vote : null;
                    // Hide the 'counters' attribute from the response
                    $gods->makeHidden('counters');
                    return $gods;
                })
                ->sortByDesc('upvotes_count')  // Order the collection by upvotes_count
                ->values();  // Re-index the collection to reset the keys
                
            return Helper::jsonResponse(true, 'Gods retrieved successfully.', 200, $gods);
        } catch (Exception $e) {
            Log::error("GodsController::index: " . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to retrieve Gods', 403);
        }
    }




    public function store(Request $request)
    {
        $validatedData = $request->validate([
            // 'anonymous_user_id' => 'required|exists:anonymous_users,id',
            'god_id' => 'required|exists:gods,id',
            'counter_god_id' => 'required|exists:gods,id',
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
            // Check if the god and counter god are the same
            if ($validatedData['god_id'] == $validatedData['counter_god_id']) {
                return Helper::jsonErrorResponse('You cannot vote for the same god. Please select a different counter god.', 403);
            }
            $anonymousUserId = $anonymousUser->id;
            $god = God::findOrFail($request->god_id);
            //if exits vote
            $godsCounter = GodsCounter::where('anonymous_user_id', $anonymousUserId)->where('god_id', $validatedData['god_id'])->where('counter_god_id', $validatedData['counter_god_id'])->first();
            if ($godsCounter) {
                if ($godsCounter->vote === $validatedData['vote']) {
                    $godsCounter->delete();
                    DB::commit();
                    return Helper::jsonErrorResponse('Your vote has been changed and deleted', 200);
                } else {
                    $godsCounter->update(['vote' => $validatedData['vote']]);
                    DB::commit();
                    return Helper::jsonResponse(true, 'Vote successfully recorded', 200);
                }
            } else {
                // Count how many counters this user already voted for this god
                $godsCounter = GodsCounter::where('god_id', $god->id)->where('anonymous_user_id', $anonymousUserId)->count();

                if ($godsCounter >= 3) {
                    return Helper::jsonErrorResponse('You can only vote for 3 counters picks aginst per god', 403);
                }

                // Store the vote
                GodsCounter::create([
                    'anonymous_user_id' => $anonymousUserId,
                    'god_id' => $validatedData['god_id'],
                    'counter_god_id' => $validatedData['counter_god_id'],
                    'vote' => $validatedData['vote']
                ]);
            }
            // Log::info('last vote' . $vote);

            DB::commit();
            return Helper::jsonResponse(true, 'Vote successfully recorded', 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("GodsCounterController::store" . $e->getMessage());
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

}
