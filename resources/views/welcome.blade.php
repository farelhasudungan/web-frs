<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FRS Portal - Course Registration System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-blue: #0056b3;
            --secondary-blue: #004085;
            --light-blue: #e3f2fd;
            --text-dark: #212529;
            --text-muted: #6c757d;
            --border-color: #dee2e6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: var(--text-dark);
            line-height: 1.6;
        }

        .header {
            background: white;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-icon {
            width: 50px;
            height: 50px;
            background: var(--primary-blue);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .logo-text h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin: 0;
        }

        .logo-text p {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin: 0;
        }

        .main-container {
            min-height: calc(100vh - 120px);
            display: flex;
            align-items: center;
            padding: 2rem 0;
        }

        .welcome-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
            max-width: 1000px;
            margin: 0 auto;
        }

        .welcome-header {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
        }

        .welcome-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .welcome-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .login-btn {
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.3);
            color: white;
            padding: 12px 40px;
            font-size: 1.1rem;
            font-weight: 500;
            border-radius: 25px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .login-btn:hover {
            background: rgba(255,255,255,0.3);
            color: white;
            transform: translateY(-2px);
        }

        .features-grid {
            padding: 3rem 2rem;
        }

        .feature-item {
            text-align: center;
            padding: 1.5rem 1rem;
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: var(--light-blue);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2rem;
            color: var(--primary-blue);
        }

        .feature-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
        }

        .feature-desc {
            font-size: 0.95rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .info-section {
            background: var(--light-blue);
            padding: 2rem;
            text-align: center;
        }

        .info-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 0.5rem;
        }

        .info-text {
            color: var(--text-muted);
            margin-bottom: 1.5rem;
        }

        .footer {
            background: white;
            border-top: 1px solid var(--border-color);
            padding: 1.5rem 0;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .welcome-title {
                font-size: 2rem;
            }
            
            .welcome-header {
                padding: 2rem 1rem;
            }
            
            .features-grid {
                padding: 2rem 1rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="logo-text">
                    <h1>FRS Portal</h1>
                    <p>Course Registration System</p>
                </div>
            </div>
        </div>
    </header>

    <div class="main-container">
        <div class="container">
            <div class="welcome-card">
                <div class="welcome-header">
                    <h1 class="welcome-title">Selamat Datang di FRS Portal</h1>
                    <p class="welcome-subtitle">
                        Sistem Registrasi Mata Kuliah Online - Kelola pendaftaran mata kuliah dengan mudah dan efisien
                    </p>
                    <a href="{{ route('login') }}" class="login-btn">
                        <i class="fas fa-sign-in-alt me-2"></i>Masuk
                    </a>
                </div>

                <div class="features-grid">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-book"></i>
                                </div>
                                <h3 class="feature-title">Manajemen Mata Kuliah</h3>
                                <p class="feature-desc">Kelola dan lihat informasi mata kuliah yang tersedia dengan lengkap</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <h3 class="feature-title">Registrasi Online</h3>
                                <p class="feature-desc">Daftar mata kuliah secara online dengan proses yang cepat dan mudah</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <h3 class="feature-title">Monitoring Progress</h3>
                                <p class="feature-desc">Pantau dan kelola mata kuliah yang telah didaftarkan dengan mudah</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Section -->
                <div class="info-section">
                    <h3 class="info-title">Akses Sistem</h3>
                    <p class="info-text">
                        Untuk mengakses sistem, silakan masuk menggunakan akun yang telah terdaftar
                    </p>
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4">
                        <i class="fas fa-arrow-right me-2"></i>Masuk ke Sistem
                    </a>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} FRS Portal - Course Registration System. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
