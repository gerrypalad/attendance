<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard')</title>

    <!-- Bootstrap 5 -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <!-- Bootstrap Icons -->
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"> --}}
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-icons.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <style>
        body{
            background:#f5f7fb;
            font-family:Inter,Segoe UI,Roboto,sans-serif;
            color:#334155;
        }

        .navbar{
            background:#fff;
            border-bottom:1px solid #e9ecef;
            min-height:72px;
        }

        .navbar-brand{
            font-weight:700;
            color:#2563eb;
            letter-spacing:.5px;
        }

        .hero{
            background:#fff;
            border-radius:16px;
            padding:20px;
            margin-top:10px;
            box-shadow:0 2px 10px rgba(0,0,0,.04);
        }

        .hero h1{
            font-weight:700;
        }

        .hero p{
            color:#64748b;
        }

        .card{
            border:none;
            border-radius:16px;
            box-shadow:0 2px 10px rgba(0,0,0,.04);
        }

        .stat-icon{
            width:55px;
            height:55px;
            display:flex;
            align-items:center;
            justify-content:center;
            border-radius:12px;
            font-size:24px;
        }

        .table{
            margin-bottom:0;
        }

        .avatar{
            width:42px;
            height:42px;
            border-radius:50%;
            object-fit:cover;
        }

        .progress{
            height:8px;
        }

        .badge-soft{
            background:#e7f1ff;
            color:#2563eb;
            padding:6px 12px;
        }

        footer{
            color:#94a3b8;
            font-size:.9rem;
        }
        .logout-link {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .logout-link:hover {
            /* background-color: #dc3545;
            color: white !important; */
        }
    </style>

</head>
<body>

    @include('layouts.navigation')

    <main class="container-fluid">

        <!-- 1. Support for traditional @section('content') views -->
        @yield('content')

        <!-- 2. Support for modern component $slot views if no section is captured -->
        @isset($slot)
            {{ $slot }}
        @endisset
    </main>

    <!-- Global Toast Notifications system -->
    <x-toast />
    <!-- FOOTER -->
    <footer class="footer mb-4" style="margin-top: 20px;">
          <div class="container-fluid text-center">
            <p class="mb-0 text-muted">ZzenitramOPC Apps</p>
            <p class="mb-0 text-muted">&copy; <script>document.write(new Date().getFullYear())</script> Designed <i class="bi bi-heart-fill" style="color:red;"></i> by: Zzenitram Consulting OPC. All rights reserved.</p>
          </div>
    </footer>
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
