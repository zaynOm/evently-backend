<?php

namespace Database\Seeders\Permissions;

use App\Enums\ROLE as ROLE_ENUM;
use App\Models\Role;
use App\Services\ACLService;
use Illuminate\Database\Seeder;

class CrudPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(ACLService $aclService)
    {
        /*
            // Here, include project specific permissions. E.G.:
            $aclService->createScopePermissions('interests', ['create', 'read', 'update', 'delete', 'import', 'export']);
            $aclService->createScopePermissions('games', ['create', 'read', 'read_own', 'update', 'delete']);

            $adminRole = Role::where('name', ROLE_ENUM::ADMIN)->first();
            $aclService->assignScopePermissionsToRole($adminRole, 'interests', ['create', 'read', 'update', 'delete', 'import', 'export']);
            $aclService->assignScopePermissionsToRole($adminRole, 'games', ['create', 'read', 'read_own', 'update', 'delete']);

            $advertiserRole = Role::where('name', 'advertiser')->first();
            $aclService->assignScopePermissionsToRole($advertiserRole, 'interests', ['read']);
            $aclService->assignScopePermissionsToRole($advertiserRole, 'games', ['create', 'read_own']);
        */

        $userRole = Role::where('name', ROLE_ENUM::USER)->first();
        $adminRole = Role::where('name', ROLE_ENUM::ADMIN)->first();

        $aclService->createScopePermissions('categories', ['create', 'read', 'update', 'delete']);
        $aclService->assignScopePermissionsToRole($userRole, 'categories', ['read']);
        $aclService->assignScopePermissionsToRole($adminRole, 'categories', ['create', 'read', 'update', 'delete']);
    }
}
