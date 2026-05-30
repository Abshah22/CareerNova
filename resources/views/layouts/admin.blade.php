<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CareerNova Admin')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
            --dark: #2d3748;
            --light: #f7fafc;
        }
        
        body {
            background-color: var(--light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .sidebar {
            background: var(--dark);
            min-height: 100vh;
            padding: 20px 0;
            position: fixed;
            width: 250px;
            left: 0;
            top: 0;
        }
        
        .sidebar .nav-link {
            color: #a0aec0;
            padding: 12px 20px;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover {
            color: white;
            background-color: rgba(102, 126, 234, 0.1);
            border-left-color: var(--primary);
        }
        
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(102, 126, 234, 0.2);
            border-left-color: var(--primary);
        }
        
        .main-content {
            margin-left: 250px;
            margin-top: 60px;
            padding: 30px;
        }
        
        .card {
            border: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-radius: 8px;
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .stat-card {
            border-left: 4px solid var(--primary);
        }
        
        .page-title {
            color: var(--dark);
            font-weight: 600;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .page-title i {
            font-size: 2rem;
            color: var(--primary);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .main-content {
                margin-left: 0;
                margin-top: 0;
            }
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="/admin/dashboard">
                <i class="fas fa-graduation-cap"></i> CareerNova Admin
            </a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3">{{ Auth::user()->name ?? 'Admin' }}</span>
                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'Admin' }}" 
                     alt="Avatar" class="rounded-circle" width="40" height="40">
            </div>
        </div>
    </nav>

    <div class="row g-0">
        <!-- Sidebar -->
        <nav class="sidebar">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}" 
                       href="/admin/dashboard">
                        <i class="fas fa-chart-line me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.analytics*') ? 'active' : '' }}" 
                       href="/admin/analytics">
                        <i class="fas fa-chart-bar me-2"></i> Analytics
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" 
                       href="/admin/users">
                        <i class="fas fa-users me-2"></i> Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.subjects*') ? 'active' : '' }}" 
                       href="/admin/subjects">
                        <i class="fas fa-book me-2"></i> Subjects
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.mcqs*') ? 'active' : '' }}" 
                       href="/admin/mcqs">
                        <i class="fas fa-question-circle me-2"></i> MCQs
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}" 
                       href="/admin/settings">
                        <i class="fas fa-cog me-2"></i> Settings
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <form action="/logout" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="nav-link" style="background: none; border: none; cursor: pointer;">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="main-content w-100">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    
    @yield('scripts')
</body>
</html>
