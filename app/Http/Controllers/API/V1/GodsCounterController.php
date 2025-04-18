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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;

class GodsCounterController extends Controller
{

    /**
     * Retrieve a paginated list of gods that counter a given god, sorted by upvotes.
     *
     * This method fetches all gods except the one identified by the provided slug.
     * For each god, it loads related counters (votes from the given god),
     * counts upvotes and downvotes from the original god,
     * and attaches whether the current anonymous user has voted on each.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $godSlug  The slug of the god for which counters are being retrieved
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the godSlug is invalid
     */
    public function getGodsCounters(Request $request, string $godSlug): JsonResponse
    {
        try {
            $per_page = $request->get('per_page', 25);
            $fingerprint = $request->header('Fingerprint');
            $anonymousUser = AnonymousUser::where('fingerprint', $fingerprint)->first();
            $god = God::where('slug', $godSlug)->firstOrFail();

            $query = God::with([
                'counters' => function ($q) use ($god) {
                    $q->where('god_id', $god->id);
                }
            ])
                ->withCount([
                    'counters as upvotes_count' => function ($query) use ($god) {
                        $query->where('god_id', $god->id)
                            ->where('counter_god_id', '!=', $god->id)
                            ->where('vote', 'up');
                    },
                    'counters as downvotes_count' => function ($query) use ($god) {
                        $query->where('god_id', $god->id)
                            ->where('counter_god_id', '!=', $god->id)
                            ->where('vote', 'down');
                    }
                ])
                ->where('status', 'active')
                ->where('id', '!=', $god->id);

            $paginatedGods = $query->get();

            // Add is_vote, vote_value, and net_votes
            $transformed = $paginatedGods->map(function ($gods) use ($anonymousUser, $god) {
                $userVote = $gods->counters->where('anonymous_user_id', optional($anonymousUser)->id)->where('god_id', $god->id)->first();
                $gods->is_vote = $userVote ? true : false;
                $gods->vote_value = $userVote ? $userVote->vote : null;
                $gods->net_votes = $gods->upvotes_count - $gods->downvotes_count;
                $gods->makeHidden(['counters', 'created_at', 'updated_at', 'aspect_description', 'sub_title', 'description_title', 'description', 'status']);
                return $gods;
            });

            // Sort by net_votes descending
            $sorted = $transformed->sortByDesc('net_votes')->values();

            // Paginate manually since we used `get()` earlier
            $page = $request->get('page', 1);
            $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
                $sorted->forPage($page, $per_page),
                $sorted->count(),
                $per_page,
                $page
            );

            return Helper::jsonResponse(true, 'Gods retrieved successfully.', 200, $paginated, true);
        } catch (Exception $e) {
            Log::error("GodsController::index: " . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to retrieve Gods. ' . $e->getMessage(), 403);
        }
    }


    /**
     * Store a new counter vote.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the godSlug is invalid
     */
    public function store(Request $request): JsonResponse
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

}
