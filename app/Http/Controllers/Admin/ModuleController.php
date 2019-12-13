<?php

namespace App\Http\Controllers\Admin;

use App\Module;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modules = Module::with('teacher')->withCount('students')->get();

        return response()->json([
            'data' => $modules
        ]);
    }

    public function teachers()
    {
        $teachers = User::where('role_id', Role::TEACHER)->get();

        return response()->json([
            'data' => $teachers
        ]);
    }

    public function students()
    {
        $students = User::where('role_id', Role::STUDENT)->get();

        return response()->json([
            'data' => $students
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $module = Module::create($request->all());

        $students = collect(explode(',', $request->students))->map(function ($student) {
            return (int) $student;
        });

        $module->students()->sync($students);

        return response()->json([
            'data' => $module
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function show(Module $module)
    {
        $module->load('students');

        return response()->json([
            'data' => $module
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Module $module)
    {
        $module->update($request->all());

        $students = collect(explode(',', $request->students))->map(function ($student) {
            return (int) $student;
        });

        $module->students()->sync($students);

        return response()->json([
            'data' => $module
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Module $module
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Module $module)
    {
        $module->delete();

        return response()->json([
            'data' => $module
        ]);
    }
}
