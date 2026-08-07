<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Resources defined in policies
        $resources = [
            'activity-log',
            'business-unit',
            'company',
            'corrective-work-order',
            'department',
            'dispatch',
            'driver',
            'employee',
            'incident',
            'maker',
            'odometer',
            'permission',
            'position',
            'preventive-work-order',
            'requesting-office',
            'role',
            'toll-fare',
            'toll-point',
            'toll-road',
            'user',
            'vehicle-category',
            'vehicle-group',
            'vehicle',
            'vehicle-power-type',
        ];

        // Standard CRUD actions matching requested naming convention
        $actions = [
            'view-any',
            'view',
            'create',
            'edit',
            'delete',
            'restore',
            'force-delete',
        ];

        // Create all permissions
        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$action}-{$resource}",
                    'guard_name' => 'web',
                ]);
            }
        }

        // Create roles
        $superAdmin = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        $dispatcher = Role::firstOrCreate(['name' => 'dispatcher', 'guard_name' => 'web']);
        $technician = Role::firstOrCreate(['name' => 'technician', 'guard_name' => 'web']);
        $user = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        // Give all permissions to Superadmin
        $superAdmin->syncPermissions(Permission::all());

        // Dispatcher permissions
        $dispatcherPermissions = [
            'view-any-dispatch', 'view-dispatch', 'create-dispatch', 'edit-dispatch', 'delete-dispatch', 'restore-dispatch',
            'view-any-driver', 'view-driver', 'create-driver', 'edit-driver',
            'view-any-vehicle', 'view-vehicle',
            'view-any-incident', 'view-incident', 'create-incident', 'edit-incident',
            'view-any-requesting-office', 'view-requesting-office',
            'view-any-odometer', 'view-odometer', 'create-odometer', 'edit-odometer',
        ];
        $dispatcher->syncPermissions($dispatcherPermissions);

        // Technician permissions
        $technicianPermissions = [
            'view-any-vehicle', 'view-vehicle', 'edit-vehicle',
            'view-any-incident', 'view-incident', 'edit-incident',
            'view-any-preventive-work-order', 'view-preventive-work-order', 'create-preventive-work-order', 'edit-preventive-work-order',
            'view-any-corrective-work-order', 'view-corrective-work-order', 'create-corrective-work-order', 'edit-corrective-work-order',
        ];
        $technician->syncPermissions($technicianPermissions);

        // User permissions
        $userPermissions = [
            'view-any-vehicle', 'view-vehicle',
            'view-any-dispatch', 'view-dispatch',
            'view-any-driver', 'view-driver',
            'view-any-incident', 'view-incident',
        ];
        $user->syncPermissions($userPermissions);

        // Assign superadmin role to John Christopher L. Llobrera
        $adminUser = User::where('email', 'jclllobrera@miescor.ph')->first();
        if ($adminUser) {
            $adminUser->assignRole($superAdmin);
        }
    }
}
