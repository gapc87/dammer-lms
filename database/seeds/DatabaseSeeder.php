<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        factory(App\Role::class)->create([
            'name' => 'admin',
            'description' => 'usuario con todos los privilegios'
        ]);

        factory(App\Role::class)->create([
            'name' => 'teacher',
            'description' => 'profesores'
        ]);

        factory(App\Role::class)->create([
            'name' => 'student',
            'description' => 'estudiantes'
        ]);

        factory(App\User::class)->create([
            'name' => 'Administrador',
            'email' => 'admin@dammer-lms.test',
            'role_id' => \App\Role::ADMIN
        ]);

        factory(App\User::class, 4)->create([
            'role_id' => \App\Role::TEACHER
        ])->each(function ($teacher) {
            $module = $teacher->teach()->save(factory(App\Module::class)->make());
            $module->students()->saveMany(factory(App\User::class, 10)->make([
                'role_id' => \App\Role::STUDENT
            ]));
            $module->resources()->save(factory(App\Resource::class)->make(), ['evaluation' => 1]);
            $module->tasks()->save(factory(App\Task::class)->make(), ['evaluation' => 1]);
        });


    }
}
