<?php

namespace App\Http\Controllers;

use App\Module;
use Illuminate\Http\Request;
use App\Http\Resources\Module as ModuleResource;

class HomeController extends Controller
{
    public function modules()
    {
        switch (auth()->user()->role->name) {
            case 'admin':
                $modules = ModuleResource::collection(Module::all());
                break;
            case 'teacher':
                $modules = ModuleResource::collection(auth()->user()->teach);
                break;
            case 'student':
                $modules = ModuleResource::collection(auth()->user()->modules);
                break;
        }
        return $modules;
    }
}
