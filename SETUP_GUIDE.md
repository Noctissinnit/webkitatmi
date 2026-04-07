# Laravel Starter Kit - Setup Guide

Starter kit ini telah dikonfigurasi dengan semua package yang diperlukan untuk memulai project Laravel dengan fitur-fitur lengkap.

## ⚠️ PENTING - JANGAN LUPA!

**Saat development, WAJIB menjalankan 2 terminal secara bersamaan:**

```bash
# Terminal 1 - Laravel Server
php artisan serve

# Terminal 2 - Vite dev server (JANGAN LUPA!)
npm run dev
```

**Jika tidak menjalankan `npm run dev`, CSS dan JavaScript tidak akan ter-compile dan perubahan tidak akan ter-reload secara otomatis.**

## 📦 Packages yang Sudah Diinstal

### 1. **Laravel Breeze (Sistem Login/Autentikasi)**
   - Framework: Blade with Alpine.js
   - Dark mode support enabled
   - Testing framework: Pest (sudah konfigurasi)
   - File yang relevan:
     - Routes: `routes/auth.php` dan `routes/web.php`
     - Views: `resources/views/auth/` (login, register, forgot password, reset password)
     - Dashboard: `resources/views/dashboard.blade.php`
     - Profile management: `resources/views/profile/`
   - User model: `app/Models/User.php` (sudah ter-setup)

   **Penggunaan:**
   - Akses login di: `/login`
   - Daftar user baru: `/register`
   - Dashboard user: `/dashboard`

### 2. **Spatie Laravel Permission (User Roles & Permissions)**
   - Version: ^6.25
   - Database tables: `roles`, `permissions`, `role_has_permissions`, `model_has_roles`, `model_has_permissions`
   - File konfigurasi: `config/permission.php`
   - Migration file: `database/migrations/2026_04_07_114241_create_permission_tables.php`

   **Fitur:**
   - Sistem role management
   - Permission control
   - Middleware untuk authorization

   **Contoh penggunaan:**
   ```php
   // Assign role to user
   $user->assignRole('admin');

   // Check permission
   $user->can('create posts');

   // Give permission
   $user->givePermissionTo('edit posts');
   ```

### 3. **mPDF (PDF Generation/Viewing)**
   - Version: ^8.3
   - Library untuk generate file PDF dari HTML
   - Tidak memerlukan external binary

   **Contoh penggunaan:**
   ```php
   use Mpdf\Mpdf;

   $mpdf = new Mpdf();
   $mpdf->WriteHTML($html);
   $mpdf->Output('document.pdf', 'D'); // Download
   ```

### 4. **Yajra Laravel DataTables**
   - Version: ^12.0
   - Package lengkap includes:
     - `yajra/laravel-datatables-oracle` - DataTable builder
     - `yajra/laravel-datatables-html` - HTML builder
     - `yajra/laravel-datatables-buttons` - Export buttons
     - `yajra/laravel-datatables-export` - Export functionality
     - `yajra/laravel-datatables-editor` - Table editor

   **Fitur:**
   - Ajax-powered data tables
   - Export ke CSV, Excel, PDF
   - Server-side processing
   - Responsive design

   **Contoh penggunaan:**
   ```php
   use DataTables;

   public function data()
   {
       return DataTables::of(User::query())->make(true);
   }

   // Di Blade
   {{ $dataTable->table() }}
   {{ $dataTable->scripts() }}
   ```

## 🚀 Quick Start

### 1. Setup Environment
```bash
cd starter-kit

# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Setup database (pastikan database sudah ter-konfigurasi di .env)
php artisan migrate

# Install npm dependencies (sudah dilakukan)
npm install
```

### 2. Jalankan Development Server
```bash
# Terminal 1 - Laravel Server
php artisan serve

# Terminal 2 - Vite dev server (untuk hot reload assets)
npm run dev
```

Akses aplikasi di: `http://localhost:8000`

### 3. Testing
```bash
# Jalankan tests dengan Pest
php artisan test

# Atau dengan vendor binary
./vendor/bin/pest
```

## 📂 Folder Structure

```
starter-kit/
├── app/
│   ├── Models/           # Database models
│   ├── Http/
│   │   ├── Controllers/  # Controllers
│   │   └── Middleware/   # Custom middleware
│   └── Providers/        # Service providers
├── config/               # Configuration files
│   └── permission.php    # Spatie permission config
├── database/
│   ├── migrations/       # Database migrations
│   ├── factories/        # Model factories
│   └── seeders/          # Seeders
├── resources/
│   ├── views/            # Blade templates
│   │   ├── auth/         # Authentication pages
│   │   ├── dashboard.blade.php
│   │   └── layouts/      # Layout templates
│   ├── css/              # CSS files
│   └── js/               # JavaScript files
├── routes/
│   ├── web.php           # Web routes
│   └── auth.php          # Breeze auth routes
├── storage/              # File storage
├── tests/                # Test files
└── vendor/               # Composer packages
```

## 🔐 Database Setup

Default menggunakan SQLite database di `database/database.sqlite`.

Untuk menggunakan database lain (MySQL, PostgreSQL), edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=starter_kit
DB_USERNAME=root
DB_PASSWORD=
```

Kemudian jalankan:
```bash
php artisan migrate
```

## 🔐 Menambah Permission dan Role

```php
// Di seed atau controller
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Create permissions
Permission::create(['name' => 'create posts']);
Permission::create(['name' => 'edit posts']);
Permission::create(['name' => 'delete posts']);

// Create roles
$adminRole = Role::create(['name' => 'admin']);
$userRole = Role::create(['name' => 'user']);

// Assign permissions to roles
$adminRole->givePermissionTo(['create posts', 'edit posts', 'delete posts']);
$userRole->givePermissionTo(['create posts']);
```

## 📊 Contoh DataTable Implementation

### Controller
```php
use DataTables;
use App\Models\User;

public function index()
{
    if (request()->ajax()) {
        $data = User::query();
        return DataTables::of($data)
            ->addColumn('action', 'users.action')
            ->rawColumns(['action'])
            ->toJson();
    }
    return view('users.index');
}
```

### Blade View
```blade
<div class="card">
    <div class="card-body">
        <table class="table table-striped" id="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("users.index") }}',
        columns: [
            {data: 'id'},
            {data: 'name'},
            {data: 'email'},
            {data: 'action', orderable: false, searchable: false}
        ]
    });
</script>
@endpush
```

## 📋 Dokumentasi External

- Laravel: https://laravel.com/docs
- Breeze: https://laravel.com/docs/breeze
- Spatie Permission: https://spatie.be/docs/laravel-permission/v6/introduction
- mPDF: https://mpdf.github.io/
- DataTables: https://yajrabox.com/docs/laravel-datatables/10.0

## ✅ Setup Checklist

- [x] Laravel 12 core installation
- [x] Laravel Breeze authentication
- [x] Spatie permission system
- [x] mPDF library
- [x] Yajra DataTables
- [x] Vite build tool setup
- [x] Pest testing framework
- [x] Dark mode support
- [x] SQLite database
- [x] npm dependencies

## 📝 Notes

- Default database: SQLite (`database/database.sqlite`)
- Default port: 8000 (Laravel server)
- Node.js packages sudah diinstall (157 packages)
- Composer packages: 94 packages main + dev

## 🎯 Next Steps

1. Create models and migrations untuk fitur aplikasi
2. Setup authentication guards dan policies
3. Create DataTables untuk menampilkan data
4. Implementasi PDF export functionality
5. Configure role dan permission sesuai kebutuhan
6. Setup email notifications
7. Add custom styling dengan Tailwind CSS

---

**Generated:** April 7, 2026
**Laravel Version:** 12.x
**PHP Version:** 8.2+
