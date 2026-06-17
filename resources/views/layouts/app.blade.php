<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SCM Apotek</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Icon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- OVERLAY -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- SIDEBAR -->
<div class="sidebar p-3" id="sidebar">

    <!-- LOGO -->
    <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
        <img src="{{ asset('img/logo-wide.png') }}" alt="Apotek Templek Sehat" style="max-width: 100%; height: auto; max-height: 55px; object-fit: contain;">
    </div>

    <!-- MENU -->
    <small class="text-muted">BERANDA</small>
    <a href="/" class="d-block py-2">
        <i class="bi bi-house"></i> Beranda
    </a>

    <small class="text-muted mt-3 d-block">MASTER</small>

    <!-- DROPDOWN STOCK -->
    <a href="#" class="d-block py-2 submenu-toggle" data-target="#stockMenu">
        <i class="bi bi-box"></i> Stock Obat
        <i class="bi bi-chevron-down float-end me-2" style="font-size: 12px; margin-top: 4px;"></i>
    </a>

    <div class="ms-3" id="stockMenu" style="{{ request()->is('obat', 'kategori', 'jenis', 'satuan') ? '' : 'display: none;' }}">
        <a href="/obat" class="d-block py-1 {{ request()->is('obat') ? 'fw-bold text-primary' : '' }}">• Data Obat</a>
        <a href="/kategori" class="d-block py-1 {{ request()->is('kategori') ? 'fw-bold text-primary' : '' }}">• Kategori Obat</a>
        <a href="/jenis" class="d-block py-1 {{ request()->is('jenis') ? 'fw-bold text-primary' : '' }}">• Jenis Obat</a>
        <a href="/satuan" class="d-block py-1 {{ request()->is('satuan') ? 'fw-bold text-primary' : '' }}">• Satuan Obat</a>
    </div>

    <a href="/supplier" class="d-block py-2 {{ request()->is('supplier') ? 'fw-bold text-primary' : '' }}">
        <i class="bi bi-person"></i> Data Supplier
    </a>

    <small class="text-muted mt-3 d-block">TRANSAKSI</small>

    <a href="/transaksi/masuk" class="d-block py-2 {{ request()->is('transaksi/masuk') ? 'fw-bold text-primary' : '' }}">
        <i class="bi bi-box-arrow-in-down"></i> Obat Masuk
    </a>

    <a href="/transaksi/keluar" class="d-block py-2 {{ request()->is('transaksi/keluar') ? 'fw-bold text-primary' : '' }}">
        <i class="bi bi-box-arrow-up"></i> Obat Keluar
    </a>

    <a href="/transaksi/kadaluarsa" class="d-block py-2 {{ request()->is('transaksi/kadaluarsa') ? 'fw-bold text-primary' : '' }}">
        <i class="bi bi-calendar-x"></i> Obat Kadaluarsa
    </a>

    <small class="text-muted mt-3 d-block">LAPORAN</small>

<!-- DROPDOWN LAPORAN BARANG -->
<a href="#" class="d-block py-2 submenu-toggle" data-target="#laporanBarang">
    <i class="bi bi-file-earmark-text"></i> Laporan Obat
    <i class="bi bi-chevron-down float-end me-2" style="font-size: 12px; margin-top: 4px;"></i>
</a>

<div class="ms-3" id="laporanBarang" style="{{ request()->is('laporan/masuk', 'laporan/keluar') ? '' : 'display: none;' }}">
    <a href="/laporan/masuk" class="d-block py-1 {{ request()->is('laporan/masuk') ? 'fw-bold text-primary' : '' }}">• Laporan Obat Masuk</a>
    <a href="/laporan/keluar" class="d-block py-1 {{ request()->is('laporan/keluar') ? 'fw-bold text-primary' : '' }}">• Laporan Obat Keluar</a>
</div>

<!-- LAPORAN PENJUALAN REMOVED -->

</div>

<!-- MAIN -->
<div class="main">

<!-- TOPBAR -->
<div class="topbar d-flex justify-content-between align-items-center">

    <!-- KIRI -->
    <div class="d-flex align-items-center flex-grow-1">
        <button class="btn btn-light d-lg-none me-2" id="sidebarToggle">
            <i class="bi bi-list fs-4"></i>
        </button>
        <div class="date-text text-truncate">
            <strong>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</strong>
        </div>
    </div>

    <!-- KANAN -->
    <div class="d-flex align-items-center gap-3 ms-auto" style="min-height: 40px; justify-content: flex-end !important;">

        <!-- NOTIF -->
        @php
            $globalExpiredCount = 0;
            $globalNearExpiredCount = 0;
            $globalNow = \Carbon\Carbon::now();
            $globalAlerts = [];

            // 1. Cek Kadaluarsa dari ObatBatch
            $globalObatKadaluarsa = \App\Models\ObatBatch::with('obat')->where('stok', '>', 0)->get();

            foreach($globalObatKadaluarsa as $b) {
                $kd = \Carbon\Carbon::parse($b->tgl_kadaluarsa);
                if ($kd->isPast()) {
                    $globalExpiredCount++;
                    $globalAlerts[] = [
                        'type' => 'expired',
                        'icon' => 'bi-exclamation-circle-fill',
                        'color' => 'text-danger',
                        'message' => 'Obat ' . $b->obat->nama . ' (' . $b->stok . ' pcs) kadaluarsa pada ' . $kd->format('d/m/Y'),
                    ];
                } elseif ($globalNow->diffInDays($kd, false) <= 90) {
                    $globalNearExpiredCount++;
                    $globalAlerts[] = [
                        'type' => 'near',
                        'icon' => 'bi-exclamation-circle-fill',
                        'color' => 'text-warning',
                        'message' => 'Obat ' . $b->obat->nama . ' (' . $b->stok . ' pcs) akan kadaluarsa pada ' . $kd->format('d/m/Y'),
                    ];
                }
            }

            // 2. Cek Stok Menipis dari Obat
            $globalObat = \App\Models\Obat::with('satuan')->get();
            foreach ($globalObat as $o) {
                $stokMin = $o->satuan ? ($o->satuan->stok_min ?? 0) : 0;
                if ($stokMin > 0 && $o->stok <= $stokMin) {
                    $globalAlerts[] = [
                        'type' => 'low_stock',
                        'icon' => 'bi-box-seam-fill',
                        'color' => 'text-warning',
                        'message' => 'Stok ' . $o->nama . ' menipis! Sisa ' . $o->stok . ' (Min: ' . $stokMin . ')',
                    ];
                }
            }

            $totalAlerts = count($globalAlerts);
        @endphp

        <div class="dropdown">
            <div class="position-relative d-flex align-items-center justify-content-center shadow-sm" data-bs-toggle="dropdown" style="cursor: pointer; width: 40px; height: 40px; border-radius: 50%; background-color: #f8fafc; border: 1px solid #e2e8f0; transition: all 0.2s;">
                <i class="bi bi-bell fs-5 text-secondary"></i>
                @if($totalAlerts > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 9px; padding: 3px 6px; z-index: 5;">
                        {{ $totalAlerts }}
                    </span>
                @endif
            </div>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="min-width: 320px; border-radius: 16px; max-height: 400px; overflow-y: auto; z-index: 10000; margin-top: 10px;">
                <li class="px-3 py-2 border-bottom">
                    <strong class="text-dark">Notifikasi Sistem</strong>
                </li>
                @forelse(array_slice($globalAlerts, 0, 5) as $alert)
                    <li class="px-3 py-2 border-bottom text-wrap" style="font-size: 13px;">
                        <span class="d-flex align-items-start">
                            <i class="bi {{ $alert['icon'] }} me-2 mt-1 {{ $alert['color'] }}"></i>
                            <span>{{ $alert['message'] }}</span>
                        </span>
                    </li>
                @empty
                    <li class="px-3 py-4 text-center text-muted" style="font-size: 13px;">
                        <i class="bi bi-check-circle-fill text-success fs-4 d-block mb-2"></i>
                        Semua stok & obat dalam kondisi aman!
                    </li>
                @endforelse
                @if($totalAlerts > 5)
                    <li class="text-center py-2">
                        <a href="/" class="text-decoration-none text-primary" style="font-size: 12px; font-weight: 600;">Lihat Detail di Dashboard ({{ $totalAlerts }})</a>
                    </li>
                @endif
            </ul>
        </div>

        <!-- PROFILE DIRECT LINK -->
        <a href="/profile" class="d-flex align-items-center justify-content-center shadow-sm rounded-circle" style="width: 40px; height: 40px; overflow: hidden; border: 2px solid #3b82f6; transition: all 0.2s;">
            <img src="{{ auth()->user()->photo 
                ? asset('storage/' . auth()->user()->photo) 
                : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                 class="w-100 h-100"
                 style="object-fit:cover;"
                 title="Lihat Profil">
        </a>

    </div>

</div>

    @yield('content')

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="{{ asset('js/app_layout.js') }}"></script>
@stack('scripts')
</body>
</html>