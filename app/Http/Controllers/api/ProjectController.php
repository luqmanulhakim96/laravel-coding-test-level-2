<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\Project;

use Validator;


class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'q' => 'string|max:100',
                'pageIndex' => 'integer',
                'pageSize' => 'integer',
                'sortBy' => 'string|max:100',
                'sortDirection' => 'string|max:4',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $limit = $request->pageSize ?? 3;
            $offset = $request->pageIndex ?? 0;
            $sortBy = $request->sortBy ?? 'name';
            $sortDirection = $request->sortDirection ?? 'ASC';

            $projects = Project::where('name', 'LIKE', '%' . $request->q . '%');
            $projects = $projects->offset($offset)
                ->limit($limit)
                ->orderBy($sortBy, $sortDirection)
                ->get();

        } catch (\Throwable $th) {
            return response()->json(['message' => $th], 404);
        }

        return response()->json($projects);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:projects',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $project = Project::create(array_merge(
                $validator->validated(),
                ['product_owner_id' => auth()->user()->id]
            ));
        } catch (\Throwable $th) {
            throw $th;
        }


        return response()->json([
            'message' => 'Project created',
            'project' => $project
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $project = Project::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'No result'], 404);
        }

        return response()->json($project);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $project = Project::findOrFail($id);
            $validator = Validator::make($request->all(), [
                'name' => ['string', Rule::unique('projects')->ignore($id),],
            ]);

            $project->fill($request->all())->save();

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

        } catch (\Throwable $th) {
            return response()->json(['message' => 'No result'], 404);
        }
        return response()->json([
            'message' => 'Project updated',
            'project' => $project
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $project = Project::findOrFail($id);

            $project->delete();
        } catch (\Throwable $th) {
            return response()->json(['message' => 'No result'], 404);
        }
        return response()->json([
            'message' => 'Project deleted',
        ], 200);
    }
}
