<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Task;

use Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $tasks = Task::get();
        } catch (\Throwable $th) {
            return response()->json(['message' => $th], 404);
        }

        return response()->json($tasks);
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
                'title' => 'string|required',
                'description' => 'string',
                'project_id' => 'required',
                'user_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $task = Task::create(array_merge(
                $validator->validated(),
                ['status' => "NOT_STARTED"]
            ));
        } catch (\Throwable $th) {
            return response()->json(['message' => $th], 404);
        }


        return response()->json([
            'message' => 'Task created',
            'task' => $task
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
            $task = Task::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'No result'], 404);
        }

        return response()->json($task);
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
            $task = Task::with('project')->findOrFail($id);

            if ($task->user_id == auth()->user()->id) {
                $validator = Validator::make($request->all(), [
                    'title' => 'prohibited',
                    'description' => 'prohibited',
                    'status' => 'required|string',
                ]);
            } else if ($task->project->product_owner_id == auth()->user()->id) {
                $validator = Validator::make($request->all(), [
                    'title' => 'string|required',
                    'description' => 'string',
                    'status' => 'required|string',
                ]);
            }


            $task->fill($request->all())->save();

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => 'No result'], 404);
        }
        return response()->json([
            'message' => 'Task updated',
            'task' => $task
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
            $task = Task::findOrFail($id);

            $task->delete();
        } catch (\Throwable $th) {
            return response()->json(['message' => 'No result'], 404);
        }
        return response()->json([
            'message' => 'Task deleted',
        ], 200);
    }
}
