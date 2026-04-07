<?php

namespace App\Http\Controllers;

use App\Models\User;
use Mpdf\Mpdf;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

/**
 * DemoController
 * 
 * Controllers ini mendemonstrasikan penggunaan semua package yang tersedia
 * di starter kit ini:
 * - Laravel Breeze (Authentication)
 * - Spatie Permission (Roles & Permissions)
 * - mPDF (PDF Generation)
 * - Yajra DataTables (Server-side datatables)
 */
class DemoController extends Controller
{
    /**
     * ==========================================
     * LARAVEL BREEZE - Authentication Examples
     * ==========================================
     */

    /**
     * Tampilkan halaman dashboard user
     */
    public function dashboard()
    {
        $user = auth()->user();
        
        return view('demo.dashboard', [
            'user' => $user,
            'roleCount' => $user->roles()->count(),
            'permissionCount' => $user->getAllPermissions()->count(),
        ]);
    }

    /**
     * Get current authenticated user info
     */
    public function getCurrentUser()
    {
        return response()->json(auth()->user());
    }

    /**
     * ==========================================
     * SPATIE PERMISSION - Role & Permission Examples
     * ==========================================
     */

    /**
     * Check user permissions example
     */
    public function checkPermissions()
    {
        $user = auth()->user();

        return response()->json([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getPermissionNames(),
            'has_admin_role' => $user->hasRole('admin'),
            'can_create_posts' => $user->can('create posts'),
            'can_delete_posts' => $user->can('delete posts'),
        ]);
    }

    /**
     * Display users with their roles
     */
    public function usersWithRoles()
    {
        $users = User::with('roles', 'permissions')->get();

        return response()->json([
            'users' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->roles->pluck('name'),
                    'permissions' => $user->getAllPermissions()->pluck('name'),
                ];
            }),
        ]);
    }

    /**
     * ==========================================
     * MPDF - PDF Generation Examples
     * ==========================================
     */

    /**
     * Generate and download PDF invoice
     */
    public function generateInvoicePdf()
    {
        try {
            $html = view('pdf.invoice', [
                'invoice_number' => 'INV-2026-001',
                'customer_name' => auth()->user()->name,
                'customer_email' => auth()->user()->email,
                'issue_date' => now(),
                'due_date' => now()->addDays(30),
                'items' => [
                    ['description' => 'Product A', 'quantity' => 2, 'unit_price' => 100, 'total' => 200],
                    ['description' => 'Product B', 'quantity' => 1, 'unit_price' => 150, 'total' => 150],
                    ['description' => 'Service C', 'quantity' => 3, 'unit_price' => 50, 'total' => 150],
                ],
                'subtotal' => 500,
                'tax_rate' => 10,
                'tax_amount' => 50,
                'total' => 550,
            ])->render();

            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'P',
            ]);

            $mpdf->WriteHTML($html);

            return response($mpdf->Output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="invoice-' . now()->format('Y-m-d') . '.pdf"'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Generate and preview PDF report
     */
    public function previewReportPdf()
    {
        try {
            $users = User::all();

            $html = view('pdf.report', [
                'title' => 'User Report',
                'generated_at' => now(),
                'users' => $users,
                'total_users' => $users->count(),
            ])->render();

            $mpdf = new Mpdf();
            $mpdf->WriteHTML($html);

            return response($mpdf->Output(), 200, [
                'Content-Type' => 'application/pdf'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * ==========================================
     * YAJRA DATATABLES - Server-side Examples
     * ==========================================
     */

    /**
     * Get users list for DataTable (AJAX)
     */
    public function getUsersDataTable(Request $request)
    {
        if ($request->ajax()) {
            $data = User::query()->select(['id', 'name', 'email', 'created_at']);

            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return '<a class="btn btn-sm btn-primary" href="javascript:void(0)">Edit</a> ' .
                           '<button class="btn btn-sm btn-danger" onclick="deleteUser(' . $row->id . ')">Delete</button>';
                })
                ->addColumn('roles', function ($row) {
                    return $row->roles->pluck('name')->implode(', ');
                })
                ->addColumn('status', function ($row) {
                    return '<span class="badge bg-success">Active</span>';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i:s');
                })
                ->rawColumns(['action', 'roles', 'status'])
                ->make(true);
        }
    }

    /**
     * Display DataTable page
     */
    public function dataTableIndex()
    {
        return view('demo.datatable-index');
    }

    /**
     * ==========================================
     * COMBINED EXAMPLES - Using all together
     * ==========================================
     */

    /**
     * Get user summary with all features
     */
    public function getCompleteSummary()
    {
        $user = auth()->user();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
            ],
            'authentication' => [
                'is_authenticated' => auth()->check(),
                'auth_guard' => auth()->getDefaultDriver(),
            ],
            'permissions' => [
                'roles' => $user->roles->pluck('name'),
                'permissions' => $user->getAllPermissions()->pluck('name'),
                'has_admin_role' => $user->hasRole('admin'),
            ],
            'available_features' => [
                'authentication' => 'Laravel Breeze installed ✓',
                'permissions' => 'Spatie Permission system ready ✓',
                'pdf_generation' => 'mPDF available ✓',
                'datatables' => 'Yajra DataTables ready ✓',
            ],
            'endpoints' => [
                'dashboard' => '/demo/dashboard',
                'check_permissions' => '/demo/permissions',
                'users_list' => '/demo/users',
                'generate_pdf' => '/demo/pdf/invoice',
                'datatable' => '/demo/datatable',
            ],
        ]);
    }
}
