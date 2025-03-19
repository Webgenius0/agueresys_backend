<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Ability;
use App\Models\Category;
use App\Models\God;
use App\Models\GodRole;
use DB;
use Exception;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class GodsController extends Controller
{
    /**
     * Display a listing of the units with DataTables support.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = God::with('abilities')->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('thumbnail', function ($data) {
                    return '<img src="' . asset($data->thumbnail) . '" class="wh-40 rounded-3" alt="no image found">';
                })
                ->addColumn('status', function ($data) {
                    $status = '<div class="form-check form-switch">';
                    $status .= '<input onclick="changeStatus(event,' . $data->id . ')" type="checkbox" class="form-check-input" style="border-radius: 25rem;width:40px"' . $data->id . '" name="status"';

                    if ($data->status == "active") {
                        $status .= ' checked';
                    }

                    $status .= '>';
                    $status .= '</div>';

                    return $status;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="action-wrapper">
                    <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View" onclick="window.location.href=\'' . route('gods.show', $data->id) . '\'">
                        <i class="material-symbols-outlined fs-16 text-primary">visibility</i>
                        </button>
                        
                        <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="deleteRecord(event,' . $data->id . ')">
                        <i class="material-symbols-outlined fs-16 text-danger">delete</i>
                        </button>
             
                </div>';
                })
                ->rawColumns(['thumbnail', 'status', 'action'])
                ->make(true);
        }
        return view("backend.layouts.god.index");
    }
    // <a type="button" href="' . route('gods.edit', $data->id) . '" class="ps-0 border-0 bg-transparent lh-1 position-relative top-2"
    // ><i class="material-symbols-outlined fs-16 text-body">edit</i></a>
    /**
     * Show the form for creating a new data.
     */
    public function create()
    {
        return view("backend.layouts.god.create");
    }

    /**
     * Store a newly created data in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255|unique:gods,title',
            'sub_title' => 'required|string|max:255',
            'description_title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'aspect_description' => 'required|string|max:10000',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'aspect_images.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'aspect_images' => 'required',
        ]);

        try {
            DB::beginTransaction();
            // thumbnail
            if ($request->hasFile('thumbnail')) {
                $validatedData['thumbnail'] = Helper::fileUpload($request->file('thumbnail'), 'gods', time() . '_' . getFileName($request->file('thumbnail')));
            }
            // aspect images
            $aspectImages = [];
            if (isset($validatedData['aspect_images'])) {
                if ($validatedData['aspect_images']) {
                    foreach ($validatedData['aspect_images'] as $key => $image) {
                        $imagePath = Helper::fileUpload($image, 'aspect_images', $key . '_' . getFileName($image));
                        $aspectImages[] = $imagePath;
                    }
                }
            }

            // $validatedData['aspect_images'] = json_encode($aspectImages);
            $god = God::Create([
                'title' => $validatedData['title'],
                'sub_title' => $validatedData['sub_title'],
                'description_title' => $validatedData['description_title'],
                'description' => $validatedData['description'],
                'aspect_description' => $validatedData['aspect_description'],
                'thumbnail' => $validatedData['thumbnail'],
            ]);
            foreach ($aspectImages as $key => $image) {
                Ability::Create([
                    'god_id' => $god->id,
                    'ability_thumbnail' => $image,
                ]);
            }

            DB::commit();
            flash()->success('Gods created successfully');
            return redirect()->route('gods.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("GodsController::store" . $e->getMessage());
            flash()->error('Something went wrong' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $data = God::findOrFail($id);
            return view("backend.layouts.god.edit", compact("data"));
        } catch (Exception $e) {
            Log::error("GodsController::edit" . $e->getMessage());
            flash()->error('Something went wrong' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $data = God::with([
                'abilities',
                'godRoles' => function ($query) {
                    $query->with('role')->withCount(['upvotes', 'downvotes']);
                }
            ])
            ->withCount('viewers')
            ->findOrFail($id);
            // dd($data->toArray());
            return view("backend.layouts.god.show", compact("data"));
        } catch (Exception $e) {
            Log::error("GodsController::show" . $e->getMessage());
            flash()->error('Something went wrong' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, God $god)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255|unique:gods,title,' . $god->id,
            'sub_title' => 'required|string|max:255',
            'description_title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'aspect_description' => 'required|string|max:10000',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'aspect_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Handle thumbnail update
            if ($request->hasFile('thumbnail')) {
                $validatedData['thumbnail'] = Helper::fileUpload($request->file('thumbnail'), 'gods', time() . '_' . getFileName($request->file('thumbnail')));
                // Delete old thumbnail if needed
                if ($god->thumbnail) {
                    Helper::fileDelete(public_path($god->thumbnail));
                }
            } else {
                $validatedData['thumbnail'] = $god->thumbnail;
            }

            // Update the God record
            $god->update([
                'title' => $validatedData['title'],
                'sub_title' => $validatedData['sub_title'],
                'description_title' => $validatedData['description_title'],
                'description' => $validatedData['description'],
                'aspect_description' => $validatedData['aspect_description'],
                'thumbnail' => $validatedData['thumbnail'],
            ]);

            // Handle aspect images update
            if ($request->hasFile('aspect_images')) {
                // Delete old aspect images if needed
                // foreach ($god->abilities as $ability) {
                //     Helper::fileDelete(public_path($ability->ability_thumbnail));
                //     $ability->delete();
                // }

                // Upload new images
                $aspectImages = [];
                foreach ($request->file('aspect_images') as $key => $image) {
                    $imagePath = Helper::fileUpload($image, 'aspect_images', $key . '_' . getFileName($image));
                    $aspectImages[] = $imagePath;
                }

                // Recreate abilities with new aspect images
                foreach ($aspectImages as $image) {
                    Ability::create([
                        'god_id' => $god->id,
                        'ability_thumbnail' => $image,
                    ]);
                }
            }

            DB::commit();
            flash()->success('God updated successfully');
            return redirect()->route('gods.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("GodsController::update" . $e->getMessage());
            flash()->error('Something went wrong: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = God::findOrFail($id);

        // delete the god
        if (!empty($data->thumbnail)) {
            Helper::fileDelete(public_path($data->thumbnail));

        }
        $aspects = Ability::where('god_id', $data->id)->get();
        if (!empty($aspects)) {
            foreach ($aspects as $aspect) {
                if (!empty($aspect->ability_thumbnail)) {
                    Helper::fileDelete(public_path($aspect->ability_thumbnail));
                }
            }
        }
        // delete the god
        $data->delete();

        return response()->json([
            "success" => true,
            "message" => "Item deleted successfully."
        ]);
    }

    /**
     * Change the status of the specified resource from storage.
     */
    public function status(Request $request, $id)
    {
        $data = God::find($id);

        // check if the category exists
        if (empty($data)) {
            return response()->json([
                "success" => false,
                "message" => "Item not found."
            ], 404);
        }

        // toggle status of the category
        if ($data->status == 'active') {
            $data->status = 'inactive';
        } else {
            $data->status = 'active';
        }

        // save the changes
        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'Item status changed successfully.'
        ]);
    }
}
