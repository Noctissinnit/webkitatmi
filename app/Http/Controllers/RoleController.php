<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.roles.index');
    }

    /**
     * Get roles data for DataTables
     */
    public function getRoles()
    {
        $roles = Role::with('permissions')->select('roles.*');

        return DataTables::of($roles)
            ->addIndexColumn()
            ->addColumn('permissions', function ($row) {
                $permissionsBadge = '';
                if ($row->permissions->count() > 0) {
                    foreach ($row->permissions as $permission) {
                        $permissionsBadge .= '<span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-200 text-gray-900">' . htmlspecialchars($permission->name) . '</span> ';
                    }
                } else {
                    $permissionsBadge = '<span class="text-gray-400 text-sm">No permissions</span>';
                }
                return $permissionsBadge;
            })
            ->addColumn('created_date', function ($row) {
                return $row->created_at->format('d M Y');
            })
            ->addColumn('action', function ($row) {
                $editBtn = '<a href="' . route('roles.edit', $row->id) . '" title="Edit role" style="color:#6b7280;text-decoration:none;font-weight:500;font-size:0.85rem;padding:0;transition:color 0.2s ease;display:inline-flex;align-items:center;gap:0.375rem;border-bottom:2px solid transparent;">';
                $editBtn .= '<i class="fas fa-edit w-4 h-4"></i> Edit</a>';
                
                $deleteBtn = '';
                if ($row->name !== 'admin') {
                    $deleteBtn = '<form action="' . route('roles.destroy', $row->id) . '" method="POST" style="display:inline;" onsubmit="return confirm(\'Are you sure?\');">';
                    $deleteBtn .= '<input type="hidden" name="_method" value="DELETE">';
                    $deleteBtn .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
                    $deleteBtn .= '<button type="submit" title="Delete role" style="color:#9ca3af;text-decoration:none;font-weight:500;font-size:0.85rem;padding:0;transition:color 0.2s ease;display:inline-flex;align-items:center;gap:0.375rem;border-bottom:2px solid transparent;background:none;border:none;cursor:pointer;margin-left:1.5rem;">';
                    $deleteBtn .= '<i class="fas fa-trash w-4 h-4"></i> Delete</button></form>';
                }
                
                return '<div class="action-links justify-center" style="display:flex;gap:1.5rem;align-items:center;justify-content:center;">' . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['permissions', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();
        
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name|max:255',
            'permissions' => 'array',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()->route('roles.index')
                        ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return view('roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array',
        ]);

        $role->update([
            'name' => $validated['name'],
        ]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('roles.index')
                        ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // Prevent deleting admin role
        if ($role->name === 'admin') {
            return redirect()->route('roles.index')
                            ->with('error', 'Cannot delete admin role.');
        }

        $role->delete();

        return redirect()->route('roles.index')
                        ->with('success', 'Role deleted successfully.');
    }
}
