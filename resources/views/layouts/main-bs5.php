<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modern Corporate Dashboard</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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
            padding:60px;
            margin-top:30px;
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
    </style>
</head>
<body>

<!-- NAVIGATION -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">

        <!-- Logo Left -->
        <a class="navbar-brand" href="#">
            <i class="bi bi-grid-fill me-2"></i>
            CorpDash
        </a>

        <button class="navbar-toggler"
                data-bs-toggle="collapse"
                data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Center Navigation -->
        <div class="collapse navbar-collapse" id="navMenu">

            <ul class="navbar-nav mx-auto">

                <li class="nav-item">
                    <a class="nav-link active" href="#">Dashboard</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Projects</a>
                </li>

                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle"
                       href="#"
                       data-bs-toggle="dropdown">
                        Reports
                    </a>

                    <ul class="dropdown-menu">

                        <li><a class="dropdown-item" href="#">Sales</a></li>

                        <li><a class="dropdown-item" href="#">Finance</a></li>

                        <li><a class="dropdown-item" href="#">Marketing</a></li>

                    </ul>

                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Analytics</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Support</a>
                </li>

            </ul>

            <!-- Right User -->
            <div class="dropdown">

                <a href="#"
                   class="d-flex align-items-center text-decoration-none dropdown-toggle"
                   data-bs-toggle="dropdown">

                    <img src="https://i.pravatar.cc/80"
                         class="avatar me-2">

                    <div class="text-start">

                        <div class="fw-semibold">
                            John Smith
                        </div>

                        <small class="text-muted">
                            Administrator
                        </small>

                    </div>

                </a>

                <ul class="dropdown-menu dropdown-menu-end">

                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-person me-2"></i>
                            Profile
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-gear me-2"></i>
                            Settings
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <a class="dropdown-item text-danger" href="#">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Logout
                        </a>
                    </li>

                </ul>

            </div>

        </div>

    </div>
</nav>

<div class="container py-4">

    <!-- HERO -->
    <section class="hero">

        <div class="row align-items-center">

            <div class="col-lg-8">

                <span class="badge badge-soft mb-3">
                    Corporate Dashboard
                </span>

                <h1 class="display-5 mb-3">
                    Welcome back, John 👋
                </h1>

                <p class="lead">
                    Monitor performance, manage projects, and review
                    your company's latest business insights from one
                    modern dashboard.
                </p>

                <button class="btn btn-primary btn-lg mt-3">
                    View Reports
                </button>

            </div>

            <div class="col-lg-4 text-center">

                <i class="bi bi-bar-chart-line"
                   style="font-size:160px;color:#dbeafe;"></i>

            </div>

        </div>

    </section>

    <!-- STATS -->
    <div class="row mt-4 g-4">

        <div class="col-lg-3">

            <div class="card p-4">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="text-muted">
                            Revenue
                        </small>

                        <h3 class="mt-2">$126,300</h3>

                    </div>

                    <div class="stat-icon bg-primary-subtle text-primary">

                        <i class="bi bi-currency-dollar"></i>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="card p-4">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="text-muted">
                            Customers
                        </small>

                        <h3 class="mt-2">8,742</h3>

                    </div>

                    <div class="stat-icon bg-success-subtle text-success">

                        <i class="bi bi-people"></i>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="card p-4">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="text-muted">
                            Projects
                        </small>

                        <h3 class="mt-2">124</h3>

                    </div>

                    <div class="stat-icon bg-warning-subtle text-warning">

                        <i class="bi bi-kanban"></i>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="card p-4">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="text-muted">
                            Growth
                        </small>

                        <h3 class="mt-2">24%</h3>

                    </div>

                    <div class="stat-icon bg-info-subtle text-info">

                        <i class="bi bi-graph-up-arrow"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- CONTENT -->
    <div class="row mt-4 g-4">

        <!-- TABLE -->
        <div class="col-lg-8">

            <div class="card">

                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0">
                        Recent Projects
                    </h5>
                </div>

                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead class="table-light">

                        <tr>

                            <th>Project</th>

                            <th>Owner</th>

                            <th>Status</th>

                            <th>Progress</th>

                        </tr>

                        </thead>

                        <tbody>

                        <tr>

                            <td>Corporate Website</td>

                            <td>John</td>

                            <td><span class="badge bg-success">Completed</span></td>

                            <td>100%</td>

                        </tr>

                        <tr>

                            <td>CRM Platform</td>

                            <td>Emily</td>

                            <td><span class="badge bg-warning text-dark">In Progress</span></td>

                            <td>72%</td>

                        </tr>

                        <tr>

                            <td>Marketing Portal</td>

                            <td>Michael</td>

                            <td><span class="badge bg-primary">Planning</span></td>

                            <td>18%</td>

                        </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <!-- SIDEBAR -->
        <div class="col-lg-4">

            <div class="card p-4">

                <h5 class="mb-4">
                    Team Performance
                </h5>

                <div class="mb-4">

                    <div class="d-flex justify-content-between mb-2">
                        <span>Development</span>
                        <strong>92%</strong>
                    </div>

                    <div class="progress">
                        <div class="progress-bar bg-primary"
                             style="width:92%"></div>
                    </div>

                </div>

                <div class="mb-4">

                    <div class="d-flex justify-content-between mb-2">
                        <span>Marketing</span>
                        <strong>81%</strong>
                    </div>

                    <div class="progress">
                        <div class="progress-bar bg-success"
                             style="width:81%"></div>
                    </div>

                </div>

                <div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Sales</span>
                        <strong>74%</strong>
                    </div>

                    <div class="progress">
                        <div class="progress-bar bg-warning"
                             style="width:74%"></div>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<footer class="py-4 text-center">
    © 2026 Corporate Dashboard. All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
