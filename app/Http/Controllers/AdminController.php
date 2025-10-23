<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // middleware role sudah dipakai di routes; ini double-check opsional
    }

    /**
     * Tampilkan halaman dashboard admin.
     */
    public function dashboard()
    {
        // Gunakan view admin.dashboard (buat file view sesuai)
        // jika belum ada view, kita bisa kembalikan simple view sementara
        if (view()->exists('admin.dashboard')) {
            return view('admin.dashboard');
        }

        // Minimal fallback — kembalikan data ringkas
        $data = [
            'title' => 'Admin Dashboard',
            'message' => 'Welcome to Admin Dashboard. Create view: resources/views/admin/dashboard.blade.php',
        ];

        return view('admin.fallback-dashboard', $data);
    }
}
