<div id="app">
    <div id="sidebar">
        <div class="sidebar-wrapper active">
            <div class="sidebar-header position-relative">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="logo">
                        <a href="index.html"><img src="{{ asset('./assets/compiled/svg/logo.svg') }}" alt="Logo"
                                srcset=""></a>
                    </div>
                    <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                        <!-- Theme toggle icons remain the same -->
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20" height="20"
                            preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                            <!-- Theme toggle SVG paths -->
                        </svg>
                        <div class="form-check form-switch fs-6">
                            <input class="form-check-input me-0" type="checkbox" id="toggle-dark"
                                style="cursor: pointer">
                            <label class="form-check-label"></label>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            aria-hidden="true" role="img" class="iconify iconify--mdi" width="20" height="20"
                            preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                            <!-- Moon icon SVG path -->
                        </svg>
                    </div>
                    <div class="sidebar-toggler x">
                        <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                    </div>
                </div>
            </div>
            <div class="sidebar-menu">
                <ul class="menu">
                    <li class="sidebar-title">Menu</li>

                    <li class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}" class='sidebar-link'>
                            <i class="bi bi-grid-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <!-- User Management -->
                    <li class="sidebar-item has-sub {{ request()->is('admin/user*') ? 'active' : '' }}">
                        <a href="#" class="sidebar-link">
                            <i class="bi bi-people-fill"></i>
                            <span>Manajemen User</span>
                        </a>
                        <ul class="submenu {{ request()->is('user*') ? 'active' : '' }}">
                            <li class="submenu-item {{ request()->routeIs('user.index') ? 'active' : '' }}">
                                <a href="{{ route('user.index') }}">Admin</a>
                            </li>
                            <li class="submenu-item {{ request()->routeIs('user/wali*') ? 'active' : '' }}">
                                <a href="{{ route('user.wali') }}">User/Wali</a>
                            </li>
                        </ul>
                    </li>


                    <!-- Santri Management -->
                    <li
                        class="sidebar-item has-sub {{ request()->routeIs('santri*') || request()->routeIs('update-status.index') ? 'active' : '' }}">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-person-vcard-fill"></i>
                            <span>Manajemen Santri</span>
                        </a>
                        <ul class="submenu">
                            <li class="submenu-item {{ request()->routeIs('update-status.index') ? 'active' : '' }}">
                                <a href="{{ route('update-status.index') }}">Update Status</a>
                            </li>
                            <li class="submenu-item {{ request()->routeIs('santri.index') ? 'active' : '' }}">
                                <a href="{{ route('santri.index') }}">Data Santri</a>
                            </li>
                        </ul>
                    </li>


                    <!-- Master Data -->
                    <li
                        class="sidebar-item has-sub {{ request()->routeIs('tahun-ajaran.index', 'kategori-tagihan.index', 'bank.index', 'sekolah.index', 'program.index') ? 'active' : '' }}">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-database-fill"></i>
                            <span>Data Master</span>
                        </a>
                        <ul class="submenu">
                            <li class="submenu-item {{ request()->routeIs('tahun-ajaran.index') ? 'active' : '' }}">
                                <a href="{{ route('tahun-ajaran.index') }}">Tahun Ajaran</a>
                            </li>
                            <li class="submenu-item {{ request()->routeIs('kategori-tagihan.index') ? 'active' : '' }}">
                                <a href="{{ route('kategori-tagihan.index') }}">Kategori Tagihan</a>
                            </li>
                            <li class="submenu-item {{ request()->routeIs('bank.index') ? 'active' : '' }}">
                                <a href="{{ route('bank.index') }}">Info Bank</a>
                            </li>
                            <li class="submenu-item {{ request()->routeIs('sekolah.index') ? 'active' : '' }}">
                                <a href="{{ route('sekolah.index') }}">Sekolah</a>
                            </li>
                            <li class="submenu-item {{ request()->routeIs('program.index') ? 'active' : '' }}">
                                <a href="{{ route('program.index') }}">Program</a>
                            </li>
                        </ul>
                    </li>



                    <!-- Billing & Payments -->
                    <li class="sidebar-item has-sub 
                    {{ request()->routeIs('tagihan-santri*') 
                        || request()->routeIs('tagihan-massal.index') 
                        || request()->routeIs('pembayaran.index') 
                        || request()->routeIs('konfirmasi.pembayaran.index') 
                        || request()->routeIs('riwayat-pembayaran*') 
                        ? 'active' : '' }}">
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-cash-stack"></i>
                        <span>Tagihan & Pay</span>
                    </a>
                    <ul class="submenu">
                        <li class="submenu-item {{ request()->routeIs('tagihan-santri.index') ? 'active' : '' }}">
                            <a href="{{ route('tagihan-santri.index') }}">Buat Tagihan</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('tagihan-massal.index') ? 'active' : '' }}">
                            <a href="{{ route('tagihan-massal.index') }}">Tagihan Massal</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('pembayaran.index') ? 'active' : '' }}">
                            <a href="{{ route('pembayaran.index') }}">Pembayaran</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('konfirmasi.pembayaran.index') ? 'active' : '' }}">
                            <a href="{{ route('konfirmasi.pembayaran.index') }}">Konfirmasi Pembayaran</a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('riwayat-pembayaran*') ? 'active' : '' }}">
                            <a href="{{ route('riwayat-pembayaran.index') }}">Riwayat Pembayaran</a>
                        </li>
                    </ul>
                </li>
                


                    <!-- Reports -->
                    <li class="sidebar-item">
                        <a href="laporan.html" class='sidebar-link'>
                            <i class="bi bi-file-earmark-bar-graph-fill"></i>
                            <span>Laporan</span>
                        </a>
                    </li>

                    <!-- Settings -->
                    <li class="sidebar-item">
                        <a href="pengaturan.html" class='sidebar-link'>
                            <i class="bi bi-gear-fill"></i>
                            <span>Pengaturan</span>
                        </a>
                    </li>

                    <!-- Additional static items -->
                    <li class="sidebar-title">Operasional</li>
                    <li class="sidebar-item">
                        <a href="backup.html" class='sidebar-link'>
                            <i class="bi bi-cloud-arrow-up-fill"></i>
                            <span>Backup Data</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('audits.index') ? 'active' : ''}}">
                        <a href="" class='sidebar-link'>
                            <i class="bi bi-clock-history"></i>
                            <span>Log Aktivitas</span>
                        </a>
                    </li>
                    <!-- Logout -->
                    <li class="sidebar-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="sidebar-link w-100 text-start border-0 bg-transparent">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>