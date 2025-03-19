<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\GodsResource;
use App\Models\AnonymousUser;
use App\Models\God;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GodsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $per_page = $request->has('per_page') ? $request->per_page : 25;
            $fingerprint = $request->header('Fingerprint');
            $anonymousUser = AnonymousUser::where('fingerprint', $fingerprint)->first();
            $roleSearch = $request->has('role_search') ? $request->role_search : '';
            $titleSearch = $request->has('title_search') ? $request->title_search : '';

            $gods = God::with([
                'godRoles' => function ($q) use ($anonymousUser, $roleSearch) {
                    $q->select('id', 'role_id', 'god_id')
                        ->withCount(['votes as vote_count'])
                        ->when($roleSearch, function ($query) use ($roleSearch) {
                            $query->where('role_id', $roleSearch);
                        })
                        ->orderByDesc('vote_count') // Order roles by vote count
                        ->with([
                            'votes' => function ($q) use ($anonymousUser) {
                                $q->select('id', 'vote', 'god_role_id', 'anonymous_user_id');
                                if ($anonymousUser) {
                                    $q->where('anonymous_user_id', $anonymousUser->id);
                                }
                            }
                        ]);
                }
            ])
                ->where('status', 'active')
                ->select('id', 'title', 'sub_title', 'description_title', 'description', 'aspect_description', 'thumbnail')
                ->withCount([
                    'godRoles as max_vote_count' => function ($q) use ($roleSearch) {
                        $q->select(DB::raw('COUNT(*)'))
                            ->join('votes', 'votes.god_role_id', '=', 'god_roles.id')
                            ->when($roleSearch, function ($query) use ($roleSearch) {
                                $query->where('god_roles.role_id', $roleSearch);
                            });
                    }
                ])
                ->when($titleSearch, function ($query) use ($titleSearch) {
                    $query->where('title', 'like', '%' . $titleSearch . '%');
                })
                ->orderByDesc('max_vote_count') // Order gods by the highest role votes
                ->paginate($per_page);

            // Add is_voted field to each god role
            $gods->getCollection()->transform(function ($god) use ($anonymousUser) {
                foreach ($god->godRoles as $role) {
                    $vote = $role->votes->first();
                    $role->is_voted = $vote ? true : false;
                    $role->vote_value = $vote ? $vote->vote : null; // Upvote (1) or Downvote (-1)
                }
                return $god;
            });

            return Helper::jsonResponse(true, 'Gods retrieved successfully.', 200, GodsResource::collection($gods), true);
        } catch (Exception $e) {
            Log::error("GodsController::index: " . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to retrieve Gods', 403);
        }
    }



    // public function index(Request $request)
    // {
    //     try {
    //         $per_page = $request->get('per_page', 25);
    //         $fingerprint = $request->header('Fingerprint');
    //         $anonymousUser = AnonymousUser::where('fingerprint', $fingerprint)->first();

    //         $gods = God::with([
    //             'godRoles.votes' => function ($q) use ($anonymousUser) {
    //                 $q->select('id', 'vote', 'god_role_id', 'anonymous_user_id');
    //                 if ($anonymousUser) {
    //                     $q->where('anonymous_user_id', $anonymousUser->id);
    //                 }
    //             },
    //             'godRoles' => fn($q) => $q->select('id', 'role_id', 'god_id')
    //         ])
    //             ->where('status', 'active')
    //             ->select('id', 'title', 'sub_title', 'description_title', 'description', 'aspect_description', 'thumbnail')
    //             ->paginate($per_page);

    //         // Add is_voted field to each god
    //         $gods->getCollection()->transform(function ($god) use ($anonymousUser) {
    //             foreach ($god->godRoles as $role) {
    //                 $vote = $role->votes->first();
    //                 $role->is_voted = $vote ? true : false;
    //                 $role->vote_value = $vote ? $vote->vote : null; 
    //             }
    //             return $god;
    //         });

    //         return Helper::jsonResponse(true, 'Gods retrieved successfully.', 200, $gods, true);
    //     } catch (Exception $e) {
    //         Log::error("GodsController::index: " . $e->getMessage());
    //         return Helper::jsonErrorResponse('Failed to retrieve Gods', 403);
    //     }
    // }



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
