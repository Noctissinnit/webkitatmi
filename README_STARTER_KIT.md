# 🚀 Laravel Starter Kit - Complete Setup

Starter kit ini telah dikonfigurasi dengan semua dependencies yang diperlukan untuk membangun aplikasi Laravel profesional dengan fitur autentikasi, permission system, PDF generation, dan data tables.

## ✅ Checklist Instalasi

Semua paket berikut telah berhasil diinstal:

- ✅ **Laravel Framework** (v12.x) - Core framework
- ✅ **Laravel Breeze** (v2.4) - Authentication scaffolding dengan Blade + Alpine.js
- ✅ **Spatie Permission** (v6.25) - Role & Permission management
- ✅ **mPDF** (v8.3) - PDF generation library
- ✅ **Yajra DataTables** (v12.0) - Server-side DataTables
- ✅ **Pest** (v3.8.6) - Testing framework
- ✅ **Tailwind CSS** + **Vite** - Modern frontend tooling
- ✅ **Livewire** (v4.2) - Single file reactive components

## 📋 File Structure

```
starter-kit/
├── README.md                          # Overview (file ini)
├── SETUP_GUIDE.md                     # Panduan instalasi detail
├── USAGE_GUIDE.md                     # Panduan penggunaan fitur
├── FEATURES.md                        # Daftar fitur tersedia
│
├── app/
│   ├── Http/Controllers/
│   │   ├── DemoController.php         # 🆕 Controller demo semua features
│   │   ├── Auth/                      # Breeze auth controllers
│   │   └── ...
│   ├── Models/
│   │   ├── User.php                   # User model (sudah setup roles/permissions)
│   │   └── ...
│   └── ...
│
├── resources/
│   ├── views/
│   │   ├── auth/                      # Login, register, password reset
│   │   ├── dashboard.blade.php        # User dashboard
│   │   ├── profile/                   # Edit profile
│   │   ├── pdf/
│   │   │   ├── invoice.blade.php      # 🆕 Sample invoice template
│   │   │   └── report.blade.php       # 🆕 Sample report template
│   │   └── layouts/
│   │       └── app.blade.php          # Main layout
│   │
│   ├── css/
│   │   └── app.css                    # Custom CSS
│   └── js/
│       └── app.js                     # Custom JavaScript
│
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2026_04_07_114241_create_permission_tables.php
│   │   └── ...
│   └── seeders/
│       └── DatabaseSeeder.php
│
├── routes/
│   ├── web.php                        # Web routes
│   ├── auth.php                       # Breeze auth routes
│   └── ...
│
├── database.sqlite                    # SQLite database file
├── .env                               # Environment variables
├── composer.json                      # PHP dependencies
├── package.json                       # Node.js dependencies
└── vite.config.js                     # Vite configuration
```

## 🚀 Quick Start

### 1. Jalankan Development Server

```bash
cd starter-kit

# Terminal 1: Laravel Development Server
php artisan serve

# Terminal 2: Vite Dev Server (untuk hot reload)
npm run dev
```

Akses aplikasi di: **http://localhost:8000**

### 2. Setup Database (Optional)

Jika ingin menggunakan database lain (MySQL/PostgreSQL) selain SQLite:

```bash
# Edit .env
# Ganti DB_CONNECTION, DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD

# Jalankan migrations
php artisan migrate

# (Optional) Jalankan seeder untuk sample data
php artisan db:seed
```

### 3. Buat User Test

```bash
# Akses Laravel Tinker Shell
php artisan tinker

# Create user
>>> User::create(['name' => 'Admin', 'email' => 'admin@test.com', 'password' => bcrypt('password')])

# Exit
>>> exit
```

Atau langsung gunakan fitur register di `/register`

## 📚 Feature Documentation

### 1️⃣ **Autentikasi (Laravel Breeze)**

**Routes yang tersedia:**
- `/login` - Halaman login
- `/register` - Registrasi user baru
- `/forgot-password` - Lupa password
- `/reset-password` - Reset password
- `/dashboard` - Dashboard user (protected)
- `/profile` - Edit profil user (protected)
- `/logout` - Logout

**File konfigurasi:**
- `app/Models/User.php` - User model
- `routes/auth.php` - Auth routes
- `resources/views/auth/` - Auth views
- `app/Http/Middleware/Authenticate.php` - Auth middleware

### 2️⃣ **Permission System (Spatie)**

**Konsep:**
- **Permissions**: Aksi spesifik (create, read, update, delete)
- **Roles**: Kumpulan permissions (admin, user, moderator)
- **Users**: Memiliki role dan/atau permissions

**Setup roles & permissions:**

```php
// Edit atau buat seeder baru
php artisan make:seeder RolePermissionSeeder

// Dalam seeder:
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

Permission::create(['name' => 'create-posts']);
Permission::create(['name' => 'edit-posts']);
Permission::create(['name' => 'delete-posts']);

$admin = Role::create(['name' => 'admin']);
$admin->givePermissionTo('create-posts', 'edit-posts', 'delete-posts');

$user = Role::create(['name' => 'user']);
$user->givePermissionTo('create-posts');
```

**Penggunaan di Controller:**

```php
public function __construct()
{
    $this->middleware('permission:create-posts')->only('create', 'store');
    $this->middleware('permission:edit-posts')->only('edit', 'update');
}
```

**Penggunaan di View:**

```blade
@can('create-posts')
    <button class="btn btn-primary">Create Post</button>
@endcan

@role('admin')
    <p>Admin-only content</p>
@endrole
```

### 3️⃣ **PDF Generation (mPDF)**

**Buat PDF dari HTML:**

```php
use Mpdf\Mpdf;

public function generatePdf()
{
    $html = view('pdf.invoice', ['data' => $data])->render();
    
    $mpdf = new Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output('invoice.pdf', 'D'); // Download
}
```

**View PDF:**

```html
<!-- resources/views/pdf/invoice.blade.php -->
<html>
    <body>
        <h1>Invoice #{{ $invoice_id }}</h1>
        <p>Total: ${{ $total }}</p>
    </body>
</html>
```

### 4️⃣ **DataTables (Yajra)**

**Setup DataTable di Controller:**

```php
use Yajra\DataTables\Facades\DataTables;

public function getUsers()
{
    $data = User::query()->select(['id', 'name', 'email', 'created_at']);
    
    return DataTables::of($data)
        ->addColumn('action', 'users.action')
        ->editColumn('created_at', fn($row) => $row->created_at->format('Y-m-d'))
        ->rawColumns(['action'])
        ->make(true);
}
```

**Di Blade view:**

```html
<table class="table" id="users-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
    </thead>
</table>

<script>
    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/users/data',
        columns: [
            {data: 'id'},
            {data: 'name'},
            {data: 'email'},
            {data: 'action'}
        ]
    });
</script>
```

## 🎯 Demo Controller

Lihat `app/Http/Controllers/DemoController.php` untuk contoh penggunaan semua fitur:

- Autentikasi user
- Permission checking
- PDF generation
- DataTables

## 📦 NPM Scripts

```bash
# Jalankan development server dengan hot reload
npm run dev

# Build untuk production
npm run build

# View built assets
npm run preview
```

## 🔧 Artisan Commands

```bash
# Migrations
php artisan migrate           # Run migrations
php artisan migrate:rollback  # Rollback
php artisan migrate:fresh     # Fresh + seed

# Database
php artisan db:seed           # Run seeders
php artisan tinker            # Interactive shell

# Create files
php artisan make:model Post   # Create model
php artisan make:controller PostController
php artisan make:migration create_posts_table
php artisan make:seeder PostSeeder

# Testing
php artisan test              # Run tests with Pest

# Misc
php artisan route:list        # View all routes
php artisan cache:clear       # Clear cache
php artisan key:generate      # Generate app key
```

## 📖 External Documentation

- **Laravel**: https://laravel.com/docs/12.x
- **Breeze**: https://laravel.com/docs/12.x/starter-kits
- **Spatie Permission**: https://spatie.be/docs/laravel-permission
- **mPDF**: https://mpdf.github.io/
- **DataTables**: https://yajrabox.com/docs/laravel-datatables
- **Tailwind CSS**: https://tailwindcss.com/docs
- **Alpine.js**: https://alpinejs.dev/

## ⚠️ Important Notes

1. **Database**: Default menggunakan SQLite. Untuk production, gunakan MySQL/PostgreSQL
2. **Environment**: Edit `.env` file untuk konfigurasi aplikasi
3. **Permissions**: Perlu di-setup terlebih dahulu (lihat seeder)
4. **Testing**: Gunakan `php artisan test` untuk menjalankan tests Pest
5. **Assets**: Run `npm run build` sebelum deploy ke production

## 🎓 Next Steps

1. Baca `SETUP_GUIDE.md` untuk detail instalasi
2. Baca `USAGE_GUIDE.md` untuk contoh penggunaan
3. Baca dokumentasi feature di atas
4. Lihat `DemoController.php` untuk real-world examples
5. Mulai build aplikasi Anda!

## 📞 Support

Untuk dokumentasi lebih lengkap, kunjungi:
- Official Laravel Documentation
- Paket documentation individual (lihat di atas)

## 📝 Changelog

```
2026-04-07 (Initial Setup)
- ✅ Laravel 12 installation
- ✅ Laravel Breeze setup
- ✅ Spatie Permission installed
- ✅ mPDF library added
- ✅ Yajra DataTables configured
- ✅ Pest testing framework setup
- ✅ Sample views dan controllers created
- ✅ Documentation completed
```

---

**Happy Coding!** 🎉 Semoga starter kit ini membantu Anda membangun aplikasi Laravel yang awesome!

Jika ada pertanyaan atau butuh bantuan, jangan ragu untuk merge dengan dokumentasi resmi atau community channels.
