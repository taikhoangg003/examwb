<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$member = Role::create([
    		'name' => 'Member',
    		'slug' => 'member',
    		'permissions' => [
    			'user.update' => true,
    		]
    	]);
    	$admin = Role::create([
    		'name' => 'Admin', 
    		'slug' => 'admin',
    		'permissions' => [
    			'user.update' => true,
    			'user.users' => true,
    		]
    	]);
    }
}
