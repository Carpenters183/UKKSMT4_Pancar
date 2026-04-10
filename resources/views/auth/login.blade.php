<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login | Sistem Aspirasi Sekolah</title>

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
</head>
<body class="login-page">

    <!-- ══ HEADER ══ -->
    <div class="login-header box-shadow">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="brand-logo">
                <a href="{{ route('login') }}">
                    <img src="{{ asset('assets/vendors/images/deskapp-logo.svg') }}" alt="">
                </a>
            </div>
            <div class="login-menu">
                <ul>
                    <li><span class="text-muted" style="font-size:13px;">Sistem Aspirasi Sekolah</span></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- ══ FORM ══ -->
    <div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-md-6 col-lg-7">
                    <img src="{{ asset('assets/vendors/images/login-page-img.png') }}" alt="">
                </div>

                <div class="col-md-6 col-lg-5">
                    <div class="login-box bg-white box-shadow border-radius-10">

                        <div class="login-title">
                            <h2 class="text-center text-primary">Sistem Aspirasi Sekolah</h2>
                            <p class="text-center text-muted" style="font-size:13px;">
                                Masukkan Email / NIP / NIS untuk masuk
                            </p>
                        </div>

                        {{-- Error messages --}}
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <ul class="mb-0 pl-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            {{-- Field login: email / NIP / NIS --}}
                            <div class="input-group custom">
                                <input
                                    type="text"
                                    name="login"
                                    class="form-control form-control-lg @error('login') is-invalid @enderror"
                                    placeholder="Email / NIP / NIS"
                                    value="{{ old('login') }}"
                                    required
                                    autofocus
                                >
                                <div class="input-group-append custom">
                                    <span class="input-group-text">
                                        <i class="icon-copy dw dw-user1"></i>
                                    </span>
                                </div>
                                @error('login')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="input-group custom">
                                <input
                                    type="password"
                                    name="password"
                                    class="form-control form-control-lg"
                                    placeholder="Password"
                                    required
                                >
                                <div class="input-group-append custom">
                                    <span class="input-group-text">
                                        <i class="dw dw-padlock1"></i>
                                    </span>
                                </div>
                            </div>

                            {{-- Remember me --}}
                            <div class="row pb-30">
                                <div class="col-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="remember_me" name="remember">
                                        <label class="custom-control-label" for="remember_me">Ingat Saya</label>
                                    </div>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="input-group mb-0">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                                            <i class="dw dw-next-2 mr-2"></i> Masuk
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>

                        {{-- Info role --}}
                        <div class="mt-4 pt-3 border-top">
                            <p class="text-muted text-center mb-2" style="font-size:12px;">Petunjuk login:</p>
                            <div class="d-flex justify-content-around">
                                <span style="font-size:11px;padding:4px 10px;border-radius:6px;background:#e3f2fd;color:#1565c0;border:1px solid #90caf9;">
                                    Admin — Email
                                </span>
                                <span style="font-size:11px;padding:4px 10px;border-radius:6px;background:#e8f5e9;color:#2e7d32;border:1px solid #a5d6a7;">
                                    Guru — NIP / Email
                                </span>
                                <span style="font-size:11px;padding:4px 10px;border-radius:6px;background:#fff3e0;color:#e65100;border:1px solid #ffcc80;">
                                    Siswa — NIS / Email
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- DeskApp JS -->
    <script src="{{ asset('assets/vendors/scripts/core.js') }}"></script>
    <script src="{{ asset('assets/vendors/scripts/script.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/scripts/process.js') }}"></script>
    <script src="{{ asset('assets/vendors/scripts/layout-settings.js') }}"></script>
</body>
</html>
