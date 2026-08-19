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

        // Standard CRUD actions
        $actions = [
            'view-any',
            'view',
            'create',
            'edit',
            'delete',
            'restore',
            'force-delete',
        ];

        // Create all permissions in the system
        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$action}-{$resource}",
                    'guard_name' => 'web',
                ]);
            }
        }

        // 1. Create Roles
        $superAdmin = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        $dispatcher = Role::firstOrCreate(['name' => 'dispatcher', 'guard_name' => 'web']);
        $technician = Role::firstOrCreate(['name' => 'technician', 'guard_name' => 'web']);
        $viewer = Role::firstOrCreate(['name' => 'viewer', 'guard_name' => 'web']); // Added for Finance Head
        $user = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        // 2. Assign Permissions to Roles

        // Superadmin: All CRU Access for Fleet and Dispatch, Add Driver details
        $superAdmin->syncPermissions(Permission::all());

        // Dispatcher: Create dispatch, edit trips, incident reporting. NO Fleet/Vehicle creation.
        $dispatcherPermissions = [
            'view-any-dispatch', 'view-dispatch', 'create-dispatch', 'edit-dispatch',
            'view-any-incident', 'view-incident', 'create-incident', 'edit-incident',
            'view-any-vehicle', 'view-vehicle', // View only for Fleet
            'view-any-driver', 'view-driver',
        ];
        $dispatcher->syncPermissions($dispatcherPermissions);

        // Technician: Create/Edit vehicle, create/edit work order, incident reporting. NO Dispatch.
        $technicianPermissions = [
            'view-any-vehicle', 'view-vehicle', 'create-vehicle', 'edit-vehicle',
            'view-any-preventive-work-order', 'view-preventive-work-order', 'create-preventive-work-order', 'edit-preventive-work-order',
            'view-any-corrective-work-order', 'view-corrective-work-order', 'create-corrective-work-order', 'edit-corrective-work-order',
            'view-any-incident', 'view-incident', 'create-incident', 'edit-incident',
            // Omitted dispatch permissions explicitly
        ];
        $technician->syncPermissions($technicianPermissions);

        // Viewer (Finance Head): Display access only for Dispatch list, Workorder list
        $viewerPermissions = [
            'view-any-dispatch', 'view-dispatch',
            'view-any-preventive-work-order', 'view-preventive-work-order',
            'view-any-corrective-work-order', 'view-corrective-work-order',
            'view-any-vehicle', 'view-vehicle',
        ];
        $viewer->syncPermissions($viewerPermissions);

        // Standard User (Technical Staff / Drivers): Minimal access
        $userPermissions = [
            'view-any-vehicle', 'view-vehicle',
            'view-any-dispatch', 'view-dispatch',
        ];
        $user->syncPermissions($userPermissions);

        // 3. Assign superadmin role to you (Admin User)
        $adminUser = User::where('email', 'jclllobrera@miescor.ph')->first();
        if ($adminUser) {
            $adminUser->assignRole($superAdmin);
        }

        // 4. Map the Specific Users to their New Roles based on the exact CSV Data
        $roleMapping = [
            'mgrimando@miescor.ph' => 'dispatcher',   // MARVIN RIMANDO
            'jjmbatac@miescor.ph' => 'technician',   // JOHN JERIC BATAC
            'jggillego@miescor.ph' => 'technician',   // JAPHET GILLEGO
            'rmjala@miescor.ph' => 'technician',   // ROGACIANO JALA
            'gclachica@miescor.ph' => 'dispatcher',   // GEMMA LACHICA
            'andrea.johnson@miescor.ph' => 'user',         // ANDREA JOHNSON (Technical Staff - N/A)
            'cdebrada@miescor.ph' => 'technician',   // CHARLES EBRADA
            'rtadenic@miescor.ph' => 'superadmin',   // RAMON ADENIC (Fleet Head - All CRU)
            'rvpaguia@miescor.ph' => 'viewer',       // RICARDO V. PAGUIA II (Finance Head - Display Only)
        ];

        foreach ($roleMapping as $email => $roleName) {
            $u = User::where('email', $email)->first();
            if ($u) {
                // Ensure they only have the mapped role
                $u->syncRoles([$roleName]);
            }
        }

        // 5. Ensure all standard drivers get the basic 'user' role
        $mappedEmails = array_merge(['jclllobrera@miescor.ph'], array_keys($roleMapping));
        $drivers = User::whereNotIn('email', $mappedEmails)->get();

        foreach ($drivers as $driverUser) {
            if (! $driverUser->hasAnyRole(Role::all())) {
                $driverUser->assignRole($user);
            }
        }
    }
}
