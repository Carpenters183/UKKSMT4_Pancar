<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Sistem Aspirasi Sekolah')</title>

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/vendors/images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/vendors/images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/vendors/images/favicon-16x16.png') }}">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- DeskApp CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/styles/core.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/styles/icon-font.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/styles/style.css') }}">

    @yield('styles')
</head>
<body class="sidebar-mini">
    <!-- ══════════════════════════════════
         HEADER
    ══════════════════════════════════ -->
    <div class="header">
        <div class="header-left">
            <div class="menu-icon dw dw-menu"></div>
        </div>
        <div class="header-right">
            <div class="user-info-dropdown">
                <div class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="user-icon">
                            @php
                                $user = Auth::user();
                                $fotoUrl = null;
                                if ($user->role == 'siswa' && $user->siswa && $user->siswa->foto) {
                                    $fotoUrl = asset('storage/' . $user->siswa->foto);
                                } elseif ($user->role == 'guru' && $user->guru && $user->guru->foto) {
                                    $fotoUrl = asset('storage/' . $user->guru->foto);
                                }
                            @endphp
                            @if($fotoUrl)
                                <img src="{{ $fotoUrl }}" alt="Profile" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
                            @else
                                <img src="{{ asset('assets/vendors/images/photo1.jpg') }}" alt="">
                            @endif
                        </span>
                        <span class="user-name">
                            @if($user->role == 'siswa' && $user->siswa)
                                {{ $user->siswa->nama ?? $user->email }}
                            @elseif($user->role == 'guru' && $user->guru)
                                {{ $user->guru->nama ?? $user->email }}
                            @else
                                {{ $user->email }}
                            @endif
                        </span>
                         <small>
                        @if($user->role == 'admin')
                            <span class="badge badge-primary" style="font-size:10px;">Administrator</span>
                        @elseif($user->role == 'guru')
                            <span class="badge badge-success" style="font-size:10px;">Guru</span>
                        @else
                            <span class="badge badge-info" style="font-size:10px;">Siswa</span>
                        @endif
                    </small>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                        <a class="dropdown-item" href="{{ route('profile.my-account') }}">
                            <i class="dw dw-user1"></i> My Account
                        </a>
                        <a class="dropdown-item" href="{{ route('profile.settings') }}">
                            <i class="dw dw-settings2"></i> Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                            <button type="submit" class="dropdown-item" style="background:none;border:none;width:100%;text-align:left;cursor:pointer;padding:.375rem 1.5rem;">
                                <i class="dw dw-logout"></i> Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Notifikasi --}}
            @php
                use App\Models\Aspirasi;
                use App\Models\Progres;
                $notifUser = Auth::user();
                $notifCount = 0;
                $notifList  = [];

                if ($notifUser->role == 'admin' || $notifUser->role == 'guru') {
                    $notifCount = Aspirasi::where('status', 'Menunggu')->count();
                    foreach (Aspirasi::with('user.siswa')->where('status','Menunggu')->latest()->take(5)->get() as $asp) {
                        $notifList[] = [
                            'icon'    => 'dw dw-alarm',
                            'title'   => 'Aspirasi baru masuk',
                            'message' => 'Dari: ' . ($asp->user->siswa->nama ?? $asp->user->email),
                            'time'    => $asp->created_at->diffForHumans(),
                            'link'    => $notifUser->role == 'admin'
                                ? route('admin.pengaduan.detail', $asp->id_aspirasi)
                                : route('guru.aspirasi.detail', $asp->id_aspirasi),
                        ];
                    }
                } elseif ($notifUser->role == 'siswa') {
                    $aspirasiIds = Aspirasi::where('user_id', $notifUser->id)->pluck('id_aspirasi');
                    $notifCount  = Progres::whereIn('id_aspirasi', $aspirasiIds)->where('created_at','>=',now()->subDays(7))->count();
                    foreach (Progres::whereIn('id_aspirasi', $aspirasiIds)->latest()->take(5)->get() as $prog) {
                        $isFeedback = str_contains($prog->keterangan_progres, 'Feedback:');
                        $notifList[] = [
                            'icon'    => $isFeedback ? 'dw dw-chat' : 'dw dw-checked',
                            'title'   => $isFeedback ? 'Feedback baru' : 'Update progres',
                            'message' => Str::limit(str_replace('Feedback: ','',$prog->keterangan_progres), 55),
                            'time'    => $prog->created_at->diffForHumans(),
                            'link'    => route('siswa.aspirasi.detail', $prog->id_aspirasi),
                        ];
                    }
                }
            @endphp

            <div class="dashboard-setting user-notification">
                <div class="dropdown">
                    <a class="dropdown-toggle no-arrow" data-toggle="dropdown" href="#" role="button">
                        <i class="dw dw-notification"></i>
                        @if($notifCount > 0)
                            <span class="badge notification-active"></span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" style="min-width:300px;">
                        <div class="notification-list mx-h-250 customscroll">
                            <ul>
                                @forelse($notifList as $notif)
                                    <li>
                                        <a href="{{ $notif['link'] }}">
                                            <div class="icon">
                                                <i class="{{ $notif['icon'] }}"></i>
                                            </div>
                                            <div class="content">
                                                <div class="notification-heading">{{ $notif['title'] }}</div>
                                                <div class="notification-text">{{ $notif['message'] }}</div>
                                                <div class="notification-time"><small>{{ $notif['time'] }}</small></div>
                                            </div>
                                        </a>
                                    </li>
                                @empty
                                    <li class="text-center py-3 text-muted">
                                        <i class="dw dw-notification" style="font-size:2rem;"></i>
                                        <p class="mt-2 mb-0">Tidak ada notifikasi</p>
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- ══ HEADER END ══ -->

    <!-- ══════════════════════════════════
         LEFT SIDEBAR
    ══════════════════════════════════ -->
    <div class="left-side-bar">
        <div class="brand-logo">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/vendors/images/deskapp-logo.svg') }}" alt="" class="dark-logo">
                <img src="{{ asset('assets/vendors/images/deskapp-logo.svg') }}" alt="" class="light-logo">
            </a>
            <div class="close-sidebar" data-toggle="left-sidebar-close">
                <i class="ion-close-round"></i>
            </div>
        </div>

        {{-- User info sidebar --}}
        
        <div class="menu-block customscroll">
            <div class="sidebar-menu">
                <ul id="accordion-menu">

                    {{-- ════ MENU ADMIN ════ --}}
                    @if(Auth::user()->role == 'admin')
                        <li class="dropdown-divider">Navigasi Utama</li>

                        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard') }}" class="dropdown-toggle no-arrow">
                                <span class="micon dw dw-house-2"></span>
                                <span class="mtext">Dashboard</span>
                            </a>
                        </li>

                        <li class="dropdown-divider">Manajemen Data</li>

                        <li class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                            <a href="{{ route('admin.users') }}" class="dropdown-toggle no-arrow">
                                <span class="micon dw dw-user"></span>
                                <span class="mtext">Manajemen Pengguna</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.kategori*') ? 'active' : '' }}">
                            <a href="{{ route('admin.kategori') }}" class="dropdown-toggle no-arrow">
                                <span class="micon dw dw-tag"></span>
                                <span class="mtext">Manajemen Kategori</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.pengaduan*') ? 'active' : '' }}">
                            <a href="{{ route('admin.pengaduan') }}" class="dropdown-toggle no-arrow">
                                <span class="micon dw dw-warning"></span>
                                <span class="mtext">Data Pengaduan</span>
                            </a>
                        </li>
                    @endif

                    {{-- ════ MENU GURU ════ --}}
                    @if(Auth::user()->role == 'guru')
                        <li class="dropdown-divider">Navigasi Utama</li>

                        <li class="{{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('guru.dashboard') }}" class="dropdown-toggle no-arrow">
                                <span class="micon dw dw-house-2"></span>
                                <span class="mtext">Dashboard</span>
                            </a>
                        </li>

                        <li class="dropdown-divider">Kelola Aspirasi</li>

                        <li class="{{ request()->routeIs('guru.aspirasi.index') ? 'active' : '' }}">
                            <a href="{{ route('guru.aspirasi.index') }}" class="dropdown-toggle no-arrow">
                                <span class="micon dw dw-chat-1"></span>
                                <span class="mtext">Data Aspirasi</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('guru.history') ? 'active' : '' }}">
                            <a href="{{ route('guru.history') }}" class="dropdown-toggle no-arrow">
                                <span class="micon dw dw-clock"></span>
                                <span class="mtext">History Aspirasi</span>
                            </a>
                        </li>
                    @endif

                    {{-- ════ MENU SISWA ════ --}}
                    @if(Auth::user()->role == 'siswa')
                        <li class="dropdown-divider">Navigasi Utama</li>

                        <li class="{{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('siswa.dashboard') }}" class="dropdown-toggle no-arrow">
                                <span class="micon dw dw-house-2"></span>
                                <span class="mtext">Dashboard</span>
                            </a>
                        </li>

                        <li class="dropdown-divider">Menu Aspirasi</li>

                        <li class="{{ request()->routeIs('siswa.aspirasi.create') ? 'active' : '' }}">
                            <a href="{{ route('siswa.aspirasi.create') }}" class="dropdown-toggle no-arrow">
                                <span class="micon dw dw-edit"></span>
                                <span class="mtext">Buat Aspirasi</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('siswa.aspirasi.status') ? 'active' : '' }}">
                            <a href="{{ route('siswa.aspirasi.status') }}" class="dropdown-toggle no-arrow">
                                <span class="micon dw dw-graph"></span>
                                <span class="mtext">Status Aspirasi</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('siswa.aspirasi.history') ? 'active' : '' }}">
                            <a href="{{ route('siswa.aspirasi.history') }}" class="dropdown-toggle no-arrow">
                                <span class="micon dw dw-clock"></span>
                                <span class="mtext">History Aspirasi</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('siswa.aspirasi.feedback') ? 'active' : '' }}">
                            <a href="{{ route('siswa.aspirasi.feedback') }}" class="dropdown-toggle no-arrow">
                                <span class="micon dw dw-speech-bubble-3"></span>
                                <span class="mtext">Feedback</span>
                            </a>
                        </li>
                    @endif

                    {{-- Logout --}}
                    <li class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" id="logout-sidebar">
                            @csrf
                            <button type="submit" class="dropdown-toggle no-arrow" style="background:none;border:none;width:100%;text-align:left;cursor:pointer;padding:10px 15px;color:inherit;">
                                <span class="micon dw dw-logout"></span>
                                <span class="mtext">Logout</span>
                            </button>
                        </form>
                    </li>

                </ul>
            </div>
        </div>
    </div>
    <div class="mobile-menu-overlay"></div>
    <!-- ══ SIDEBAR END ══ -->

    <!-- ══════════════════════════════════
         MAIN CONTENT
    ══════════════════════════════════ -->
    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Gagal!</strong> {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <ul class="mb-0 pl-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')

        </div>
    </div>
    <!-- ══ MAIN CONTENT END ══ -->

    <!-- DeskApp JS -->
    <script src="{{ asset('assets/vendors/scripts/core.js') }}"></script>
    <script src="{{ asset('assets/vendors/scripts/script.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/scripts/process.js') }}"></script>
    <script src="{{ asset('assets/vendors/scripts/layout-settings.js') }}"></script>

    @yield('scripts')
</body>
</html>
