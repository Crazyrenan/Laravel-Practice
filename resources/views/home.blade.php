<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Home</title>
    @vite('resources/css/app.css')
    <!-- AOS CSS -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <style>
        :root {
            --apple-blue: #007AFF;
            --apple-blue-dark: #0056CC;
            --apple-gray: #8E8E93;
            --apple-gray-light: #F2F2F7;
            --apple-gray-dark: #1C1C1E;
            --apple-gray-medium: #2C2C2E;
            --apple-green: #34C759;
            --apple-orange: #FF9500;
            --apple-red: #FF3B30;
            --apple-purple: #AF52DE;
            --apple-pink: #FF2D55;
            --apple-yellow: #FFCC00;
            --apple-teal: #5AC8FA;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            transition: all 0.3s ease;
        }

        .apple-blur {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .apple-card {
            background: rgba(28, 28, 30, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .apple-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .apple-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        }

        .sidebar {
            width: 280px;
            background: rgba(28, 28, 30, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            transform: translateX(0);
        }

        .sidebar.hidden {
            transform: translateX(-100%);
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        .main-content {
            margin-left: 280px;
            transition: margin-left 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            margin: 4px 12px;
            border-radius: 12px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.2s ease;
            font-weight: 500;
            cursor: pointer;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(4px);
        }

        .nav-item.active {
            background: var(--apple-blue);
            color: white;
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            margin-right: 12px;
            opacity: 0.8;
        }

        .section-title {
            font-size: 13px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 24px 16px 8px 16px;
        }

        .dropdown-section {
            margin: 4px 12px;
        }

        .dropdown-header {
            display: flex;
            align-items: center;
            justify-content: between;
            padding: 12px 16px;
            border-radius: 12px;
            color: rgba(255, 255, 255, 0.9);
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: 600;
            font-size: 14px;
        }

        .dropdown-header:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .dropdown-icon {
            width: 16px;
            height: 16px;
            margin-left: auto;
            transition: transform 0.2s ease;
        }

        .dropdown-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            padding-left: 12px;
        }

        .dropdown-content.open {
            max-height: 500px;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 8px 16px;
            margin: 2px 0;
            border-radius: 8px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 13px;
        }

        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.08);
            color: white;
            transform: translateX(2px);
        }

        .dropdown-item.active {
            background: rgba(0, 122, 255, 0.3);
            color: var(--apple-blue);
        }

        .topbar {
            height: 64px;
            background: rgba(28, 28, 30, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .search-input {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 8px 16px 8px 40px;
            color: white;
            width: 300px;
            transition: all 0.2s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--apple-blue);
            background: rgba(255, 255, 255, 0.15);
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .badge {
            background: var(--apple-orange);
            color: white;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 12px;
            margin-left: auto;
        }

        .mobile-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 40;
        }

        @media (max-width: 1024px) {
            .sidebar {
                position: fixed;
                z-index: 50;
                height: 100vh;
            }
                        
            .mobile-overlay.active {
                display: block;
            }
                        
            .main-content {
                margin-left: 0 !important;
            }
        }

        .sidebar-toggle {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 8px;
            color: white;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Modern Grid Layout */
        .modern-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 1.5rem;
            min-height: calc(100vh - 120px);
        }

        .hero-modern {
            grid-column: span 12;
            background: linear-gradient(135deg, 
                rgba(0, 122, 255, 0.1) 0%, 
                rgba(175, 82, 222, 0.1) 25%,
                rgba(255, 149, 0, 0.1) 50%,
                rgba(52, 199, 89, 0.1) 75%,
                rgba(255, 59, 48, 0.1) 100%);
            border-radius: 32px;
            padding: 3rem;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .hero-modern::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(from 0deg, 
                transparent, 
                rgba(0, 122, 255, 0.1), 
                transparent, 
                rgba(175, 82, 222, 0.1), 
                transparent);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .floating-orb {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.1));
            backdrop-filter: blur(10px);
            animation: float-orb 8s ease-in-out infinite;
        }

        .orb-1 { width: 120px; height: 120px; top: 10%; right: 15%; animation-delay: 0s; }
        .orb-2 { width: 80px; height: 80px; bottom: 20%; left: 10%; animation-delay: 2s; }
        .orb-3 { width: 60px; height: 60px; top: 60%; right: 30%; animation-delay: 4s; }

        @keyframes float-orb {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(120deg); }
            66% { transform: translateY(10px) rotate(240deg); }
        }

        .stats-modern {
            grid-column: span 12;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }

        .stat-modern {
            background: rgba(28, 28, 30, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(20px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-modern:hover {
            transform: translateY(-8px) scale(1.02);
            border-color: rgba(0, 122, 255, 0.5);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
        }

        .stat-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--color-1), var(--color-2));
            border-radius: 24px 24px 0 0;
        }

        .stat-modern.blue { --color-1: #007AFF; --color-2: #5AC8FA; }
        .stat-modern.purple { --color-1: #AF52DE; --color-2: #FF2D55; }
        .stat-modern.orange { --color-1: #FF9500; --color-2: #FFCC00; }
        .stat-modern.green { --color-1: #34C759; --color-2: #30D158; }

        .stat-icon-modern {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            position: relative;
            overflow: hidden;
        }

        .stat-icon-modern::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, var(--color-1), var(--color-2));
            opacity: 0.2;
            border-radius: 16px;
        }

        .bento-grid {
            grid-column: span 12;
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            grid-template-rows: repeat(6, 120px);
            gap: 1.5rem;
            margin: 2rem 0;
        }

        .bento-item {
            background: rgba(28, 28, 30, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(20px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .bento-item:hover {
            transform: translateY(-4px);
            border-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .bento-large { grid-column: span 6; grid-row: span 3; }
        .bento-medium { grid-column: span 4; grid-row: span 2; }
        .bento-small { grid-column: span 3; grid-row: span 2; }
        .bento-wide { grid-column: span 8; grid-row: span 2; }
        .bento-tall { grid-column: span 4; grid-row: span 4; }

        @media (max-width: 1024px) {
            .bento-large, .bento-medium, .bento-small, .bento-wide, .bento-tall {
                grid-column: span 12;
                grid-row: span 2;
            }
        }

        .gradient-text {
            background: linear-gradient(135deg, #007AFF, #AF52DE, #FF9500);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .neon-glow {
            position: relative;
        }

        .neon-glow::after {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: inherit;
            padding: 2px;
            background: linear-gradient(45deg, #007AFF, #AF52DE, #FF9500, #34C759);
           .element {
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask-composite: exclude;
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;}
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .neon-glow:hover::after {
            opacity: 1;
        }

        .data-viz {
            width: 100%;
            height: 80px;
            background: linear-gradient(90deg, 
                rgba(0, 122, 255, 0.1) 0%, 
                rgba(0, 122, 255, 0.3) 50%, 
                rgba(0, 122, 255, 0.1) 100%);
            border-radius: 8px;
            position: relative;
            overflow: hidden;
        }

        .data-viz::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 60%;
            background: linear-gradient(180deg, transparent, rgba(0, 122, 255, 0.4));
            clip-path: polygon(
                0% 100%, 8% 85%, 16% 90%, 24% 75%, 32% 80%, 
                40% 65%, 48% 70%, 56% 55%, 64% 60%, 72% 45%, 
                80% 50%, 88% 35%, 96% 40%, 100% 25%, 100% 100%
            );
            animation: wave 3s ease-in-out infinite;
        }

        @keyframes wave {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(-10px); }
        }

        .metric-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: conic-gradient(from 0deg, var(--color-1) 0deg, var(--color-2) 270deg, rgba(255, 255, 255, 0.1) 270deg);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .metric-circle::before {
            content: '';
            position: absolute;
            inset: 8px;
            border-radius: 50%;
            background: rgba(28, 28, 30, 0.9);
        }

        .metric-value {
            position: relative;
            z-index: 1;
            font-weight: 700;
            color: white;
        }

        .glass-morphism {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .holographic {
            background: linear-gradient(135deg, 
                rgba(0, 122, 255, 0.1) 0%,
                rgba(175, 82, 222, 0.1) 25%,
                rgba(255, 149, 0, 0.1) 50%,
                rgba(52, 199, 89, 0.1) 75%,
                rgba(255, 59, 48, 0.1) 100%);
            background-size: 400% 400%;
            animation: holographic 8s ease infinite;
        }

        @keyframes holographic {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .pulse-ring {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100px;
            height: 100px;
            border: 2px solid rgba(0, 122, 255, 0.3);
            border-radius: 50%;
            animation: pulse-ring 2s ease-out infinite;
        }

        @keyframes pulse-ring {
            0% {
                transform: translate(-50%, -50%) scale(0.8);
                opacity: 1;
            }
            100% {
                transform: translate(-50%, -50%) scale(2);
                opacity: 0;
            }
        }

        .interactive-button {
            background: linear-gradient(135deg, rgba(0, 122, 255, 0.2), rgba(175, 82, 222, 0.2));
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 1rem 2rem;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .interactive-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .interactive-button:hover::before {
            left: 100%;
        }

        .interactive-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 122, 255, 0.3);
        }

        .apple-button {
            background: var(--apple-blue);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .apple-button:hover {
            background: var(--apple-blue-dark);
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="bg-black text-white min-h-screen">
    <!-- Background -->
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('img/88.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-20"/>
        <div class="absolute inset-0 bg-black/60"></div>
    </div>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar fixed h-full z-50" id="sidebar">
        <!-- Sidebar Header -->
        <div class="p-6 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                    <span class="text-white font-bold">📊</span>
                </div>
                <div>
                    <h1 class="text-white font-semibold">Dashboard</h1>
                    <p class="text-gray-400 text-sm">Analytics Hub</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="flex-1 overflow-y-auto py-4">
            <!-- Overview -->
            <div class="section-title">Overview</div>
            <a href="/" class="nav-item active">
                <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
                Dashboard
            </a>

            <!-- Master Data Dropdown -->
            <div class="dropdown-section">
                <div class="dropdown-header" onclick="toggleDropdown('masterData')">
                    <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                    </svg>
                    Master Data
                    <svg class="dropdown-icon" id="masterDataIcon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                    </svg>
                </div>
                <div class="dropdown-content" id="masterDataContent">
                    <a href="/vendormaster" class="dropdown-item">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                        Vendors
                        <span class="badge">12</span>
                    </a>
                    <a href="/projectmaster" class="dropdown-item">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                        </svg>
                        Projects
                    </a>
                    <a href="/requestmaster" class="dropdown-item">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"/>
                        </svg>
                        Requests
                        <span class="badge">3</span>
                    </a>
                    <a href="/pembelianmaster" class="dropdown-item">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"/>
                        </svg>
                        Purchases
                    </a>
                </div>
            </div>

            <!-- Reporting Data Dropdown -->
            <div class="dropdown-section">
                <div class="dropdown-header" onclick="toggleDropdown('reportData')">
                    <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                    </svg>
                    Reporting
                    <svg class="dropdown-icon" id="masterDataIcon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                    </svg>
                </div>
                <div class="dropdown-content" id="reportDataContent">
                    <a href="/reported" class="dropdown-item">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                        Purchase Reporting
                        <span class="badge">12</span>
                    </a>
                    <a href="/projectmaster" class="dropdown-item">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                        </svg>
                        Projects
                    </a>
                    <a href="/requestmaster" class="dropdown-item">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"/>
                        </svg>
                        Requests
                        <span class="badge">3</span>
                    </a>
                    <a href="/pembelianmaster" class="dropdown-item">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"/>
                        </svg>
                        Purchases
                    </a>
                </div>
            </div>

            <!-- Builder Queries Dropdown -->
            <div class="dropdown-section">
                <div class="dropdown-header" onclick="toggleDropdown('builderQueries')">
                    <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                    </svg>
                    Builder Queries
                    <svg class="dropdown-icon" id="builderQueriesIcon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                    </svg>
                </div>
                <div class="dropdown-content" id="builderQueriesContent">
                    <a href="/vendor" class="dropdown-item">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                        Vendor Query
                    </a>
                    <a href="/status" class="dropdown-item">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                        </svg>
                        Status Query
                    </a>
                    <a href="/order" class="dropdown-item">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"/>
                        </svg>
                        Order Query
                    </a>
                    <a href="/product" class="dropdown-item">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                        </svg>
                        Product Avg
                    </a>
                </div>
            </div>

            <!-- Normal Queries Dropdown -->
            <div class="dropdown-section">
                <div class="dropdown-header" onclick="toggleDropdown('normalQueries')">
                    <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                    </svg>
                    Normal Queries
                    <svg class="dropdown-icon" id="normalQueriesIcon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                    </svg>
                </div>
                <div class="dropdown-content" id="normalQueriesContent">
                    <a href="/vendor2" class="dropdown-item">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                        Vendor Data
                    </a>
                    <a href="/status2" class="dropdown-item">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                        </svg>
                        Status Data
                    </a>
                    <a href="/order2" class="dropdown-item">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"/>
                        </svg>
                        Order Data
                    </a>
                    <a href="/product2" class="dropdown-item">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                        </svg>
                        Product Data
                    </a>
                </div>
            </div>

            <!-- Analytics Dropdown -->
            <div class="dropdown-section">
                <div class="dropdown-header" onclick="toggleDropdown('analytics')">
                    <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                    </svg>
                    Analytics
                    <svg class="dropdown-icon" id="analyticsIcon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                    </svg>
                </div>
                <div class="dropdown-content" id="analyticsContent">
                    <a href="/vendor_chart" class="dropdown-item">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                        </svg>
                        Vendor by Month
                    </a>
                    <a href="/profit" class="dropdown-item">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                        </svg>
                        Profit Analysis
                    </a>
                    <a href="/profitcategory" class="dropdown-item">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                        </svg>
                        Profit by Category
                    </a>
                </div>
            </div>

            <!-- Advanced Dropdown -->
            <div class="dropdown-section">
                <div class="dropdown-header" onclick="toggleDropdown('advanced')">
                    <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"/>
                    </svg>
                    Advanced
                    <svg class="dropdown-icon" id="advancedIcon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                    </svg>
                </div>
                <div class="dropdown-content" id="advancedContent">
                    <a href="/vendorjoin" class="dropdown-item">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                        Vendor Details
                    </a>
                </div>
            </div>
        </div>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-white/10">
            <a href="/settings" class="nav-item">
                <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"/>
                </svg>
                Settings
            </a>
            <a href="/logout" class="nav-item" style="color: #FF3B30;">
                <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z"/>
                </svg>
                Logout
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content relative z-10" id="mainContent">
        <!-- Top Bar -->
        <div class="topbar apple-blur flex items-center justify-between px-6">
            <div class="flex items-center gap-4">
                <button id="sidebarToggle" class="sidebar-toggle">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                    </svg>
                </button>
                                
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"/>
                    </svg>
                    <input type="text" placeholder="Search anything..." class="search-input">
                </div>
            </div>
            <div class="flex items-center gap-4">
                <button class="p-2 rounded-lg hover:bg-white/10 text-gray-300 hover:text-white relative">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                    </svg>
                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center text-xs text-white">3</span>
                </button>
                <div class="flex items-center gap-3 bg-white/10 rounded-xl p-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <span class="text-white text-sm font-semibold">U</span>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-white text-sm font-medium">User Name</p>
                        <p class="text-gray-400 text-xs">Administrator</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="p-6">
            <div class="modern-grid">
                <!-- Hero Section with Holographic Effect -->
                <div class="hero-modern holographic" data-aos="fade-up">
                    <div class="floating-orb orb-1"></div>
                    <div class="floating-orb orb-2"></div>
                    <div class="floating-orb orb-3"></div>
                    
                    <div class="relative z-10">
                        <h1 class="text-5xl font-bold mb-4">
                            <span class="gradient-text">Redefine</span> Your Data Journey
                        </h1>
                        <p class="text-xl text-white/80 mb-8 max-w-3xl">
                            Experience the future of analytics with our revolutionary dashboard that transforms complex data into beautiful, actionable insights.
                        </p>
                        <div class="flex gap-4">
                            <a href="/vendor" class="interactive-button">
                                <span>🚀</span> Launch Experience
                            </a>
                            <a href="/vendor_chart" class="interactive-button">
                                <span>📊</span> Explore Analytics
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Modern Stats -->
                <div class="stats-modern">
                    <div class="stat-modern blue neon-glow" data-aos="zoom-in" data-aos-delay="100">
                        <div class="stat-icon-modern">
                            <div class="pulse-ring"></div>
                            <span class="text-2xl relative z-10">👥</span>
                        </div>
                        <div>
                            <p class="text-white/60 text-sm font-medium mb-1">Active Vendors</p>
                            <p class="text-3xl font-bold text-white mb-2" id="vendorCount">0</p>
                            <div class="flex items-center gap-2">
                                <span class="text-green-400 text-sm">↗ +12%</span>
                                <span class="text-white/60 text-sm">vs last month</span>
                            </div>
                        </div>
                        <div class="data-viz mt-4"></div>
                    </div>

                    <div class="stat-modern purple neon-glow" data-aos="zoom-in" data-aos-delay="200">
                        <div class="stat-icon-modern">
                            <span class="text-2xl relative z-10">📈</span>
                        </div>
                        <div>
                            <p class="text-white/60 text-sm font-medium mb-1">Revenue Growth</p>
                            <p class="text-3xl font-bold text-white mb-2">$<span id="revenueCount">0</span>K</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-green-400 text-sm">↗ +23%</span>
                                    <span class="text-white/60 text-sm">this quarter</span>
                                </div>
                                <div class="metric-circle purple">
                                    <span class="metric-value text-sm">75%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="stat-modern orange neon-glow" data-aos="zoom-in" data-aos-delay="300">
                        <div class="stat-icon-modern">
                            <span class="text-2xl relative z-10">⚡</span>
                        </div>
                        <div>
                            <p class="text-white/60 text-sm font-medium mb-1">Processing Speed</p>
                            <p class="text-3xl font-bold text-white mb-2"><span id="speedCount">0</span>ms</p>
                            <div class="flex items-center gap-2">
                                <span class="text-green-400 text-sm">↗ 45% faster</span>
                                <span class="text-white/60 text-sm">than industry avg</span>
                            </div>
                        </div>
                        <div class="data-viz mt-4"></div>
                    </div>

                    <div class="stat-modern green neon-glow" data-aos="zoom-in" data-aos-delay="400">
                        <div class="stat-icon-modern">
                            <span class="text-2xl relative z-10">🎯</span>
                        </div>
                        <div>
                            <p class="text-white/60 text-sm font-medium mb-1">Success Rate</p>
                            <p class="text-3xl font-bold text-white mb-2"><span id="successCount">0</span>%</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-green-400 text-sm">↗ +8%</span>
                                    <span class="text-white/60 text-sm">improvement</span>
                                </div>
                                <div class="metric-circle green">
                                    <span class="metric-value text-sm">98%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bento Grid Layout -->
                <div class="bento-grid">
                    <!-- Large Feature Card -->
                    <div class="bento-item bento-large glass-morphism" data-aos="fade-right">
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                    <span class="text-xl">🛒</span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white">Vendor Intelligence</h3>
                                    <p class="text-white/60 text-sm">AI-powered vendor analytics</p>
                                </div>
                            </div>
                            <p class="text-white/80 mb-6">
                                Harness the power of machine learning to optimize your vendor relationships and predict market trends with unprecedented accuracy.
                            </p>
                        </div>
                        <div class="flex items-center justify-between">
                            <a href="/vendor" class="interactive-button">
                                Explore Intelligence
                            </a>
                            <div class="data-viz w-32 h-16"></div>
                        </div>
                    </div>

                    <!-- Medium Cards -->
                    <div class="bento-item bento-medium glass-morphism" data-aos="fade-up" data-aos-delay="100">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center">
                                <span class="text-lg">📊</span>
                            </div>
                            <div class="metric-circle orange">
                                <span class="metric-value text-xs">87%</span>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Status Monitoring</h3>
                        <p class="text-white/60 text-sm mb-4">Real-time delivery tracking</p>
                        <a href="/status" class="text-orange-400 hover:text-orange-300 text-sm font-medium">
                            Monitor Status →
                        </a>
                    </div>

                    <div class="bento-item bento-medium glass-morphism" data-aos="fade-up" data-aos-delay="200">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-teal-500 flex items-center justify-center">
                                <span class="text-lg">🛍️</span>
                            </div>
                            <span class="text-green-400 text-sm font-medium">+15% today</span>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Order Flow</h3>
                        <p class="text-white/60 text-sm mb-4">Streamlined order management</p>
                        <a href="/order" class="text-green-400 hover:text-green-300 text-sm font-medium">
                            View Orders →
                        </a>
                    </div>

                    <!-- Tall Analytics Card -->
                    <div class="bento-item bento-tall glass-morphism holographic" data-aos="fade-left">
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                                    <span class="text-xl">🧠</span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white">Neural Analytics</h3>
                                    <p class="text-white/60 text-sm">Deep learning insights</p>
                                </div>
                            </div>
                            
                            <div class="space-y-4 mb-6">
                                <div class="flex items-center justify-between">
                                    <span class="text-white/80 text-sm">Prediction Accuracy</span>
                                    <span class="text-purple-400 font-medium">94.7%</span>
                                </div>
                                <div class="w-full bg-white/10 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-2 rounded-full" style="width: 94.7%"></div>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-white/80 text-sm">Data Processing</span>
                                    <span class="text-blue-400 font-medium">2.3TB/day</span>
                                </div>
                                <div class="w-full bg-white/10 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-blue-500 to-cyan-500 h-2 rounded-full" style="width: 78%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <a href="/vendor_chart" class="interactive-button w-full justify-center">
                            Access Neural Network
                        </a>
                    </div>

                    <!-- Wide Feature Cards -->
                    <div class="bento-item bento-wide glass-morphism" data-aos="fade-up">
                        <div class="flex items-center justify-between h-full">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-yellow-500 to-orange-500 flex items-center justify-center">
                                        <span class="text-lg">💰</span>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-white">Profit Optimization</h3>
                                        <p class="text-white/60 text-sm">AI-driven profit maximization</p>
                                    </div>
                                </div>
                                <div class="flex gap-4">
                                    <a href="/profit" class="text-yellow-400 hover:text-yellow-300 text-sm font-medium">
                                        View Profits →
                                    </a>
                                    <a href="/profitcategory" class="text-orange-400 hover:text-orange-300 text-sm font-medium">
                                        By Category →
                                    </a>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="metric-circle yellow">
                                    <span class="metric-value text-sm">+23%</span>
                                </div>
                                <div class="data-viz w-24 h-12"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Small Action Cards -->
                    <div class="bento-item bento-small glass-morphism neon-glow" data-aos="zoom-in" data-aos-delay="300">
                        <div class="text-center">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center mx-auto mb-3">
                                <span class="text-xl">🏷️</span>
                            </div>
                            <h3 class="text-sm font-bold text-white mb-2">Product Insights</h3>
                            <a href="/product" class="text-cyan-400 hover:text-cyan-300 text-xs font-medium">
                                Analyze Products →
                            </a>
                        </div>
                    </div>

                    <div class="bento-item bento-small glass-morphism neon-glow" data-aos="zoom-in" data-aos-delay="400">
                        <div class="text-center">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-pink-500 to-purple-500 flex items-center justify-center mx-auto mb-3">
                                <span class="text-xl">👨‍💼</span>
                            </div>
                            <h3 class="text-sm font-bold text-white mb-2">Vendor Details</h3>
                            <a href="/vendorjoin" class="text-pink-400 hover:text-pink-300 text-xs font-medium">
                                Deep Dive →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <!-- Anime.js -->
    <script src="https://cdn.jsdelivr.net/npm/animejs@3.2.1/lib/anime.min.js"></script>
        
    <script>
        // Initialize AOS
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                easing: 'ease-out',
                once: false
            });
        });

        // Sidebar toggle functionality
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const mobileOverlay = document.getElementById('mobileOverlay');
        let sidebarVisible = true;

        function toggleSidebar() {
            sidebarVisible = !sidebarVisible;
                        
            if (sidebarVisible) {
                sidebar.classList.remove('hidden');
                mainContent.classList.remove('expanded');
            } else {
                sidebar.classList.add('hidden');
                mainContent.classList.add('expanded');
            }

            // Handle mobile overlay
            if (window.innerWidth <= 1024) {
                if (sidebarVisible) {
                    mobileOverlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                } else {
                    mobileOverlay.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            }
        }

        sidebarToggle.addEventListener('click', toggleSidebar);
        mobileOverlay.addEventListener('click', toggleSidebar);

        // Dropdown functionality
        function toggleDropdown(dropdownId) {
            const content = document.getElementById(dropdownId + 'Content');
            const icon = document.getElementById(dropdownId + 'Icon');
                        
            if (content.classList.contains('open')) {
                content.classList.remove('open');
                icon.style.transform = 'rotate(0deg)';
            } else {
                // Close all other dropdowns
                document.querySelectorAll('.dropdown-content').forEach(dropdown => {
                    dropdown.classList.remove('open');
                });
                document.querySelectorAll('.dropdown-icon').forEach(icon => {
                    icon.style.transform = 'rotate(0deg)';
                });
                                
                // Open clicked dropdown
                content.classList.add('open');
                icon.style.transform = 'rotate(180deg)';
            }
        }

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 1024) {
                mobileOverlay.classList.remove('active');
                document.body.style.overflow = 'auto';
                                
                // Reset sidebar visibility on desktop
                if (!sidebarVisible) {
                    sidebar.classList.remove('hidden');
                    mainContent.classList.remove('expanded');
                    sidebarVisible = true;
                }
            }
        });

        // Add active state to current page navigation
        const currentPath = window.location.pathname;
        document.querySelectorAll('.nav-item, .dropdown-item').forEach(item => {
            if (item.getAttribute('href') === currentPath) {
                item.classList.add('active');
                                
                
                const dropdownContent = item.closest('.dropdown-content');
                if (dropdownContent) {
                    dropdownContent.classList.add('open');
                    const dropdownId = dropdownContent.id.replace('Content', '');
                    const icon = document.getElementById(dropdownId + 'Icon');
                    if (icon) {
                        icon.style.transform = 'rotate(180deg)';
                    }
                }
            } else {
                item.classList.remove('active');
            }
        });

        // Initialize dropdowns as closed
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.dropdown-content').forEach(dropdown => {
                dropdown.classList.remove('open');
            });
            document.querySelectorAll('.dropdown-icon').forEach(icon => {
                icon.style.transform = 'rotate(0deg)';
            });
                        
            // Animate stats counters
            animateValue('vendorCount', 0, 124, 2000);
            animateValue('revenueCount', 0, 45.2, 2000);
            animateValue('speedCount', 0, 127, 2000);
            animateValue('successCount', 0, 98, 2000);
            
            // Add interactive hover effects
            const bentoItems = document.querySelectorAll('.bento-item');
            bentoItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px) scale(1.02)';
                });
                
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
            
            // Parallax effect for floating orbs
            document.addEventListener('mousemove', function(e) {
                const orbs = document.querySelectorAll('.floating-orb');
                const x = e.clientX / window.innerWidth;
                const y = e.clientY / window.innerHeight;
                
                orbs.forEach((orb, index) => {
                    const speed = (index + 1) * 0.5;
                    const xPos = (x - 0.5) * speed * 20;
                    const yPos = (y - 0.5) * speed * 20;
                    orb.style.transform = `translate(${xPos}px, ${yPos}px)`;
                });
            });
        });
                
        // Function to animate number counters
        function animateValue(id, start, end, duration, prefix = '') {
            const obj = document.getElementById(id);
            if (!obj) return;
                        
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                let value = Math.floor(progress * (end - start) + start);
                                
                if (id === 'revenueCount') {
                    obj.innerHTML = value.toFixed(1);
                } else {
                    obj.innerHTML = value;
                }
                                
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }
    </script>
</body>
</html>