<?php

namespace App\Http\Controllers;

use App\EvaluatedTask;
use App\Task;
use App\Module;
use App\User;
use Illuminate\Http\Request;
use Webpatser\Uuid\Uuid;

class TaskController extends Controller
{
    public function task(Module $module, Task $task)
    {
        if ($module->teacher->id === \request()->user()->id) {
            $studentsUploadedTask = $module->load(['students.evaluatedTask' => function ($query) use ($task) {
                return $query->where('task_id', $task->id);
            }])->students;

            return response()->json([
                'data' => [
                    'task' => $task,
                    'teacher' => $module->teacher,
                    'students' => $studentsUploadedTask
                ]
            ]);
        }

        $evaluated_task = $task->evaluatedTask
            ->where('student_id', '=', \request()->user()->id)
            ->where('task_id', '=', $task->id)
            ->first();

        return response()->json([
            'data' => [
                'task' => [
                    'id' => $task->id,
                    'title' => $task->title,
                    'statement' => $task->statement,
                    'evaluated_task' => $evaluated_task,
                    'start_at' => $task->start_at,
                    'end_at' => $task->end_at
                ],
                'teacher' => $module->teacher
            ]
        ]);
    }

    public function storeCompleteTask(Module $module, Task $task, Request $request)
    {
        if ( ! $module->tasks->contains($task)) {
            return response()->json([
                'failed' => 'No puedes subir una tarea en un módulo que no existe'
            ]);
        }

        $path = $request->file('file')->storeAs(
            "uploads/tasks/{$request->user()->id}/{$module->id}/{$task->id}", $request->file->getClientOriginalName()
        );

        EvaluatedTask::create([
            'uuid' => (string)Uuid::generate(),
            'upload' => $path,
            'student_id' => $request->user()->id,
            'task_id' => $task->id
        ]);

        return response()->json(['success'=>'Tarea subida con éxito']);
    }

    public function downloadCompleteTask(User $user, Module $module, Task $task, $uuid)
    {
        if ($user->id !== \request()->user()->id && $module->teacher->id !== \request()->user()->id) {
            return response()->json([
                'failed' => 'No tienes permisos para acceder a este recurso'
            ]);
        }

        if ( ! $module->tasks->contains($task)) {
            return response()->json([
                'failed' => 'El recurso no existe'
            ]);
        }

        $evaluated_task = EvaluatedTask::where('uuid', $uuid)->firstOrFail();

        $pathToFile = storage_path('app/' . $evaluated_task->upload);

        return response()->download($pathToFile);
    }
}
