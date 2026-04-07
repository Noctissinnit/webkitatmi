# Deployment & Usage Instructions

## 📖 Panduan Cepat Menggunakan Starter Kit

### 1. Login & Autentikasi (Laravel Breeze)

**Akses halaman:**
- Login: `http://localhost:8000/login`
- Register: `http://localhost:8000/register`
- Dashboard: `http://localhost:8000/dashboard`

**Fitur yang tersedia:**
- ✅ User registration
- ✅ Email verification (optional)
- ✅ Password reset
- ✅ Session management
- ✅ Profile management
- ✅ Dark mode toggle

### 2. Permission Management (Spatie)

**Konsep:**
- **Permissions**: Aksi spesifik (create post, edit post, delete post)
- **Roles**: Kumpulan permissions (admin, user, moderator)
- **User**: Diberikan role atau permission langsung

**Setup di seeder:**

```bash
php artisan make:seeder RoleAndPermissionSeeder
```

Edit `database/seeders/RoleAndPermissionSeeder.php`:

```php
<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Create permissions
        Permission::create(['name' => 'view dashboard']);
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'create posts']);
        Permission::create(['name' => 'edit posts']);
        Permission::create(['name' => 'delete posts']);

        // Create roles and assign permissions
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'view dashboard',
            'manage users',
            'create posts',
            'edit posts',
            'delete posts'
        ]);

        $user = Role::create(['name' => 'user']);
        $user->givePermissionTo([
            'view dashboard',
            'create posts',
            'edit posts'
        ]);

        $moderator = Role::create(['name' => 'moderator']);
        $moderator->givePermissionTo([
            'view dashboard',
            'create posts',
            'edit posts',
            'delete posts'
        ]);
    }
}
```

Run seeder:
```bash
php artisan db:seed --class=RoleAndPermissionSeeder
```

**Usage di Controller:**

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        // Middleware untuk check permission
        $this->middleware('permission:view dashboard', ['only' => ['index']]);
        $this->middleware('permission:create posts', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit posts', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete posts', ['only' => ['destroy']]);
    }

    public function index()
    {
        // Only users with 'view dashboard' permission
        return view('posts.index');
    }

    public function create()
    {
        // Only users with 'create posts' permission
        return view('posts.create');
    }
}
```

**Usage di Blade:**

```blade
@can('create posts')
    <a href="{{ route('posts.create') }}" class="btn btn-primary">Create Post</a>
@endcan

@canany(['edit posts', 'delete posts'])
    <button class="btn btn-warning">Edit/Delete</button>
@endcanany

@role('admin')
    <div class="admin-panel">Admin Only Section</div>
@endrole
```

### 3. PDF Generation (mPDF)

**Create Controller:**

```bash
php artisan make:controller PdfController
```

**Example usage:**

```php
<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use Illuminate\Http\Response;

class PdfController extends Controller
{
    public function generatePdf()
    {
        $html = view('pdf.invoice', [
            'invoice_number' => '001',
            'date' => now(),
            'items' => [
                ['name' => 'Item 1', 'price' => 100],
                ['name' => 'Item 2', 'price' => 200],
            ]
        ])->render();

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);

        return response($mpdf->Output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="invoice.pdf"'
        ]);
    }

    public function previewPdf()
    {
        $html = view('pdf.invoice', [])->render();

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);

        return response($mpdf->Output(), 200, [
            'Content-Type' => 'application/pdf'
        ]);
    }
}
```

**Create PDF view** (`resources/views/pdf/invoice.blade.php`):

```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; }
        .invoice { padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <div class="invoice">
        <h1>Invoice</h1>
        <p>Invoice #: {{ $invoice_number }}</p>
        <p>Date: {{ $date->format('Y-m-d') }}</p>

        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>${{ number_format($item['price'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
```

**Routes:**

```php
Route::get('/pdf/preview', [PdfController::class, 'previewPdf']);
Route::get('/pdf/download', [PdfController::class, 'generatePdf']);
```

### 4. DataTables (Yajra)

**Create DataTable:**

```bash
php artisan datatables:add User
```

**Create Controller:**

```bash
php artisan make:controller UserDataController
```

**Controller code:**

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class UserDataController extends Controller
{
    public function index()
    {
        return view('users.index');
    }

    public function getData()
    {
        $data = User::query()->select(['id', 'name', 'email', 'created_at']);

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-sm btn-primary">Edit</button>';
            })
            ->addColumn('status', function ($row) {
                return '<span class="badge bg-success">Active</span>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('Y-m-d H:i');
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }
}
```

**Routes:**

```php
Route::get('/users', [UserDataController::class, 'index'])->name('users.index');
Route::get('/users/data', [UserDataController::class, 'getData'])->name('users.data');
```

**Blade view** (`resources/views/users/index.blade.php`):

```blade
@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Users DataTable</h5>
    </div>
    <div class="card-body">
        <table class="table table-striped" id="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.0/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.0/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.0/js/dataTables.bootstrap5.min.js"></script>

<script>
$(function() {
    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("users.data") }}',
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'status', name: 'status'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order: [[0, 'desc']]
    });
});
</script>
@endpush
```

## 🔧 Common Commands

```bash
# Create migration
php artisan make:migration create_posts_table

# Create model with all related files
php artisan make:model Post -a

# Create controller
php artisan make:controller PostController

# Create seeder
php artisan make:seeder PostSeeder

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Run migrations and seeders
php artisan migrate --seed

# Clear all caches
php artisan cache:clear

# Generate API routes with controllers
php artisan make:model Post -a --api

# Serve application
php artisan serve

# View routes
php artisan route:list

# Tinker shell (interactive)
php artisan tinker

# Run tests
php artisan test

# Run specific test
php artisan test tests/Feature/PostTest.php
```

## 🎨 Tailwind CSS Customization

Edit `tailwind.config.js` untuk custom styling.

Edit `resources/css/app.css` untuk tambah CSS custom.

Build CSS untuk production:
```bash
npm run build
```

Watch CSS changes:
```bash
npm run dev
```

## 📚 Database Debugging

Use `php artisan tinker`:

```bash
php artisan tinker
```

```php
# Create user
>>> $user = User::create(['name' => 'John', 'email' => 'john@example.com', 'password' => bcrypt('password')])

# Assign role
>>> $user->assignRole('admin')

# Check permission
>>> $user->hasPermissionTo('create posts')

# Get all users
>>> User::all()

# Exit
>>> exit
```

## ✨ Best Practices

1. **Gunakan Models untuk semua database access**
2. **Implement proper validation** di controllers
3. **Gunakan service classes** untuk business logic kompleks
4. **Write tests** untuk critical functionality
5. **Use collections** untuk data manipulation
6. **Cache queries** yang heavy
7. **Optimize eager loading** dengan load relations
8. **Use transactions** untuk multi-step operations
9. **Implement proper error handling**
10. **Follow PSR standards**

---

Happy coding! 🚀
