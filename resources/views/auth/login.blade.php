<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Attendance APP | Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body{
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            background:linear-gradient(135deg,#0d6efd,#6610f2);
        }

        .login-card{
            width:100%;
            max-width:430px;
            border:none;
            border-radius:10px;
            box-shadow:0 20px 60px rgba(0,0,0,.15);
        }

        .card-body{
            padding:1.5rem;
        }

        .logo{
            width:70px;
            height:70px;
            border-radius:18px;
            background:#0d6efd;
            color:#fff;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:2rem;
            margin:auto;
        }

        .form-control,
        .input-group-text{
            height:30px;
        }

        .form-control{
            border-radius:0 10px 10px 0;
        }

        .input-group-text{
            border-radius:10px 0 0 10px;
            background:#f8f9fa;
        }

        .btn-primary{
            border-radius:10px;
            padding:12px;
            font-weight:600;
        }
    </style>
</head>
<body>

<div class="card login-card">

    <div class="card-body">

        <div class="text-center mb-4">
            <h3 class="fw-bold">Attendance APP</h3>

            <p class="text-muted mb-0">
                Sign in to your account
            </p>

        </div>

        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email Address</label>

                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-envelope"></i>
                    </span>

                    <input
                        type="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        placeholder="name@example.com"
                        required
                        autofocus>
                </div>

                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>

                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-lock"></i>
                    </span>

                    <input
                        type="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Enter your password"
                        required>
                </div>

                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

<div class="d-flex justify-content-between align-items-center mb-4">

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        Remember me
                    </label>
                </div>

            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-box-arrow-in-right me-2"></i>
                Sign In
            </button>

        </form>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
