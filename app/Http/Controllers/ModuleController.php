<?php

namespace App\Http\Controllers;

use App\User;
use App\Task;
use App\Module;
use App\Resource;
use App\EvaluatedTask;
use Webpatser\Uuid\Uuid;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index(Module $module)
    {
        switch (auth()->user()->role->name) {

            case 'teacher':

                if ($module->teacher->id !== \request()->user()->id) {
                    return response()->json([
                        'failed' => 'No tienes acceso a este módulo'
                    ]);
                }

                break;
            case 'student': {

                if ( ! $module->students->contains(\request()->user())) {
                    return response()->json([
                        'failed' => 'No tienes acceso a este módulo'
                    ]);
                }

                break;
            }
        }

        $module->load(['teacher'])->get();

        $tasks = array();
        $resources = array();

        for ($i = 0; $i <= 2; $i++) {
            $tasks[$i] = $module->tasks()
                ->whereIn('evaluations.evaluation', [$i+1])
                ->get();

            $resources[$i] = $module->resources()
                ->whereIn('evaluations.evaluation', [$i+1])
                ->get();
        }

        return response()->json([
            'data' => [
                'module' => $module,
                'evaluations' => [
                    '1' => [
                        'resources' => $resources[0],
                        'tasks' => $tasks[0],
                    ],
                    '2' => [
                        'resources' => $resources[1],
                        'tasks' => $tasks[1],
                    ],
                    '3' => [
                        'resources' => $resources[2],
                        'tasks' => $tasks[2],
                    ],
                ]
            ]
        ]);
    }

    public function storeResource(Request $request)
    {
        $path = $request->file('file')->storeAs(
            "uploads/resources/{$request->module_id}", $request->file->getClientOriginalName()
        );

        $resource = Resource::create([
            'uuid' => (string)Uuid::generate(),
            'title' => $request->title,
            'resource' => $path
        ]);

        $resource->module()->attach($request->module_id, ['evaluation' => $request->evaluation]);

        return response()->json(['success'=>'You have successfully upload file.']);
    }

    public function downloadResource(Module $module, $uuid)
    {
        $resource = Resource::where('uuid', $uuid)->firstOrFail();

        $pathToFile = storage_path('app/' . $resource->resource);

        return response()->download($pathToFile);
    }

    public function storeTask(Request $request)
    {
        $task = Task::create([
            'title' => $request->title,
            'statement' => $request->statement,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at
        ]);

        $task->module()->attach($request->module_id, ['evaluation' => $request->evaluation]);

        return response()->json(['success'=>'You have successfully upload file.']);
    }

    public function evaluate(User $user, Module $module, Task $task, Request $request)
    {
        if ($module->teacher->id !== \request()->user()->id) {
            return response()->json([
                'failed' => 'No tienes permisos para acceder a este recurso'
            ]);
        }

        if ( ! $module->tasks->contains($task)) {
            return response()->json([
                'failed' => 'El recurso no existe'
            ]);
        }

        $evaluated_task = $task->evaluatedTask
            ->where('student_id', '=', $user->id)
            ->where('task_id', '=', $task->id)
            ->first();

        $evaluated_task->update($request->all());

        return response()->json([
            'data' => $evaluated_task
        ]);
    }
}
