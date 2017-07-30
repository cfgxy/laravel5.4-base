<?php

use App\Model\Permission;
use App\Model\Role;
use App\Model\User;
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
        $superadmin = new Role();
        $superadmin->name         = 'superadmin';
        $superadmin->display_name = '超级管理员'; // optional
        $superadmin->description  = ''; // optional
        $superadmin->save();

        $admin = new Role();
        $admin->name         = 'admin';
        $admin->display_name = '管理员'; // optional
        $admin->description  = ''; // optional
        $admin->save();

        $merchant = new Role();
        $merchant->name         = 'merchant';
        $merchant->display_name = '商户'; // optional
        $merchant->description  = ''; // optional
        $merchant->save();


        $perm = new Permission();
        $perm->name = 'manage-roles';
        $perm->display_name = '角色管理';
        $perm->save();
        $superadmin->attachPermission($perm);

        $perm = new Permission();
        $perm->name = 'manage-permissions';
        $perm->display_name = '权限管理';
        $perm->save();
        $superadmin->attachPermission($perm);

        $perm = new Permission();
        $perm->name = 'manage-users';
        $perm->display_name = '用户管理';
        $perm->save();
        $admin->attachPermission($perm);

        $perm = new Permission();
        $perm->name = 'manage-user-permissions';
        $perm->display_name = '用户权限管理';
        $perm->save();
        $admin->attachPermission($perm);


        $user = new User();
        $user->fill([
            'name'      => 'Admin',
            'email'     => 'admin@admin.cn',
        ]);
        $user->password = bcrypt('admin');
        $user->save();

        $user->attachRoles([$superadmin, $admin, $merchant]);
    }
}
