<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.users.index');
    }

    /**
     * Get users data for DataTables
     */
    public function getUsers()
    {
        $users = User::with('roles')->select('users.*');

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('photo', function ($row) {
                $photoUrl = $row->photo ? asset('storage/' . $row->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($row->name);
                return '<img src="' . $photoUrl . '" alt="' . htmlspecialchars($row->name) . '" class="w-10 h-10 rounded-full object-cover border border-gray-200">';
            })
            ->addColumn('roles', function ($row) {
                $rolesBadge = '';
                if ($row->roles->count() > 0) {
                    foreach ($row->roles as $role) {
                        $rolesBadge .= '<span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-200 text-gray-900">' . htmlspecialchars($role->name) . '</span> ';
                    }
                } else {
                    $rolesBadge = '<span class="text-gray-400 text-sm">No roles</span>';
                }
                return $rolesBadge;
            })
            ->addColumn('action', function ($row) {
                $editBtn = '<a href="' . route('users.edit', $row->id) . '" title="Edit user" style="color:#6b7280;text-decoration:none;font-weight:500;font-size:0.85rem;padding:0;transition:color 0.2s ease;display:inline-flex;align-items:center;gap:0.375rem;border-bottom:2px solid transparent;">';
                $editBtn .= '<i class="fas fa-edit w-4 h-4"></i> Edit</a>';
                
                $deleteBtn = '';
                if (auth()->user()->id !== $row->id) {
                    $deleteBtn = '<form action="' . route('users.destroy', $row->id) . '" method="POST" style="display:inline;" onsubmit="return confirm(\'Delete this user?\');">';
                    $deleteBtn .= '<input type="hidden" name="_method" value="DELETE">';
                    $deleteBtn .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
                    $deleteBtn .= '<button type="submit" title="Delete user" style="color:#9ca3af;text-decoration:none;font-weight:500;font-size:0.85rem;padding:0;transition:color 0.2s ease;display:inline-flex;align-items:center;gap:0.375rem;border-bottom:2px solid transparent;background:none;border:none;cursor:pointer;margin-left:1.5rem;">';
                    $deleteBtn .= '<i class="fas fa-trash w-4 h-4"></i> Delete</button></form>';
                }
                
                return '<div class="action-links justify-center" style="display:flex;gap:1.5rem;align-items:center;justify-content:center;">' . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['photo', 'roles', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'roles' => 'nullable|array',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('users', 'public');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'photo' => $photoPath,
        ]);

        if (!empty($validated['roles'])) {
            $roles = Role::whereIn('id', $validated['roles'])->pluck('id')->toArray();
            $user->roles()->sync($roles);
        }

        return redirect()->route('users.index')
                        ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $userRoles = $user->roles->pluck('id')->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'roles' => 'nullable|array',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if ($validated['password']) {
            $updateData['password'] = bcrypt($validated['password']);
        }

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $updateData['photo'] = $request->file('photo')->store('users', 'public');
        }

        $user->update($updateData);

        // Sync roles - get Role objects by ID
        if (!empty($validated['roles'])) {
            $roles = Role::whereIn('id', $validated['roles'])->pluck('id')->toArray();
            $user->roles()->sync($roles);
        } else {
            $user->roles()->sync([]);
        }

        return redirect()->route('users.index')
                        ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deletion of the authenticated user
        if (auth()->user()->id === $user->id) {
            return redirect()->route('users.index')
                            ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('users.index')
                        ->with('success', 'User deleted successfully.');
    }
}
