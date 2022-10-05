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
    public function index()
    {
        try {
            $projects = Project::get();
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
