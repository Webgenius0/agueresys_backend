<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\GodShowResource;
use App\Http\Resources\API\V1\GodsResource;
use App\Models\AnonymousUser;
use App\Models\God;
use App\Models\GodView;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GodsController extends Controller
{

    /**
     * Display a listing of the resources.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function index(Request $request): JsonResponse
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



    public function show(Request $request, $id): JsonResponse
    {
        try {
            $fingerprint = $request->header('Fingerprint');
            $anonymousUser = AnonymousUser::where('fingerprint', $fingerprint)->first();
            // Check if the viewer has already been attached to avoid duplicates
            $this->godViewer($id, $anonymousUser);

            $god = God::with([
                'abilities' => fn($q) => $q->select('id', 'god_id', 'ability_thumbnail'),
                'godRoles' => function ($q) use ($anonymousUser) {
                    $q->select('id', 'role_id', 'god_id')
                        ->withCount([
                            'votes as vote_count',
                            'votes as upvotes' => function ($query) {
                                $query->where('vote', 'up');
                            },
                            'votes as downvotes' => function ($query) {
                                $query->where('vote', 'down');
                            },
                        ])
                        ->orderByDesc('vote_count')
                        ->with([
                            'votes' => function ($q) use ($anonymousUser) {
                                $q->select('id', 'vote', 'god_role_id', 'anonymous_user_id');
                                if ($anonymousUser) {
                                    $q->where('anonymous_user_id', $anonymousUser->id);
                                }
                            },
                            'role' => function ($q) {
                                $q->select('id', 'name');
                            }

                        ]);
                }
            ])
                ->where('status', 'active')
                ->select('id', 'title', 'sub_title', 'description_title', 'description', 'aspect_description', 'thumbnail')
                ->withCount('viewers')
                ->findOrFail($id);

            // Add is_voted field to each god role
            foreach ($god->godRoles as $role) {
                $vote = $role->votes->first();
                $role->is_voted = $vote ? true : false;
                $role->vote_value = $vote ? $vote->vote : null; // Upvote (1) or Downvote (-1)
            }

            return Helper::jsonResponse(true, 'God retrieved successfully.', 200, new GodShowResource($god));
        } catch (Exception $e) {
            Log::error("GodsController::show: " . $e->getMessage());
            return Helper::jsonErrorResponse('God not found', 404);
        }
    }

    private function godViewer($godId, $anonymousUser)
    {
        $GodViewer = GodView::where('god_id', $godId)->where('anonymous_user_id', $anonymousUser->id)->first();
        if (!$GodViewer) {
            GodView::create([
                'god_id' => $godId,
                'anonymous_user_id' => $anonymousUser->id
            ]);
        }
    }

}
