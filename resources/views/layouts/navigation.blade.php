<!-- NAVIGATION -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <!-- Brand (Icon + Text) -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <i class="bi bi-clock-history text-primary me-2 fs-4"></i>
            <span class="fw-bold text-dark">ZzenitramOPC</span>
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
                    <a class="nav-link" href="{{ route('attendance.records') }}">
                        <i class="bi bi-journal-text me-1"></i> View Attendance Records
                    </a>
                </li>

                @if(Auth::user()->is_admin)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('backups.index') }}">
                            <i class="bi bi-shield-lock me-1"></i> Backups
                        </a>
                    </li>
                @endif

            </ul>

            <!-- Right User -->
            <div class="dropdown">
                <a href="#"
                   class="d-flex align-items-center text-decoration-none dropdown-toggle"
                   data-bs-toggle="dropdown">

                    <!-- Icon Avatar -->
                    <div class="avatar-icon me-2 d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle"
                         style="width: 42px; height: 42px; font-size: 22px; flex-shrink: 0;">
                        <i class="bi bi-person-circle"></i>
                    </div>

                    <!-- User Info (One Line) -->
                    <div class="text-start">
                        <span class="fw-semibold">
                            {{ Auth::user()->name }}
                        </span>
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 ms-2">
                            {{ Auth::user()->is_admin ? 'Administrator' : 'Employee' }}
                        </span>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a href="#"
                           class="dropdown-item text-danger fw-semibold logout-link"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</nav>
