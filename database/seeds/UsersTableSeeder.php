<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->fill([
            'name'      => 'Admin',
            'email'     => 'admin@admin.cn',
        ]);
        $user->role = \App\Model\Enums\UserRole::ADMIN;
        $user->password = bcrypt('admin');
        $user->save();
    }
}
