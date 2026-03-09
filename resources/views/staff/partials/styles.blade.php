<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    :root {
        --primary-green: #0d9488; /* Teal-ish green */
        --primary-dark: #0f766e;
        --secondary-green: #ccfbf1;
        --sidebar-bg: #111827;
        --card-bg: rgba(255, 255, 255, 0.9);
        --text-dark: #1f2937;
        --text-light: #6b7280;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f3f4f6;
        color: var(--text-dark);
    }

    /* Bootstrap Overrides for Theme Consistency */
    .text-success { color: var(--primary-green) !important; }
    .bg-success { background-color: var(--primary-green) !important; }
    .border-success { border-color: var(--primary-green) !important; }
    
    .btn-success {
        background-color: var(--primary-green);
        border-color: var(--primary-green);
    }
    .btn-success:hover {
        background-color: var(--primary-dark);
        border-color: var(--primary-dark);
    }

    .btn-outline-success {
        color: var(--primary-green);
        border-color: var(--primary-green);
    }
    .btn-outline-success:hover {
        background-color: var(--primary-green);
        color: #fff;
    }

    .btn-check:checked + .btn-outline-success {
        background-color: var(--primary-green);
        border-color: var(--primary-green);
    }

    /* Sidebar Styling */
    #sidebarMenu {
        background: var(--sidebar-bg);
        /* backdrop-filter: blur(10px); */ /* Removed blur for solid dark sidebar */
        transition: transform 0.3s ease-in-out;
        z-index: 1040;
        height: 100vh;
        box-shadow: 4px 0 24px 0 rgba(0,0,0,0.1); /* Subtle shadow */
    }

    .nav-link {
        color: #9ca3af; /* Gray-400 */
        transition: all 0.2s;
        border-radius: 8px;
        font-weight: 500;
        margin-bottom: 4px;
    }

    .nav-link:hover {
        background: rgba(255, 255, 255, 0.05);
        color: #f3f4f6;
        transform: translateX(3px);
    }

    .nav-link.active {
        background: linear-gradient(90deg, rgba(13, 148, 136, 0.2) 0%, rgba(13, 148, 136, 0.05) 100%);
        color: var(--primary-green);
        border-left: 3px solid var(--primary-green);
        border-radius: 4px 8px 8px 4px;
    }

    /* Mobile Responsive Sidebar */
    @media (max-width: 767.98px) {
        #sidebarMenu {
            position: fixed;
            top: 0;
            left: 0;
            width: 75%; /* Drawer width */
            max-width: 300px;
            transform: translateX(-100%);
        }

        #sidebarMenu.show {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0 !important;
            width: 100% !important;
        }

        /* Table as Cards for Mobile */
        .table thead { display: none; }
        .table tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            background: #fff;
        }
        .table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            border-bottom: 1px solid #eee;
            text-align: right;
        }
        .table tbody td:last-child { border-bottom: none; }
        .table tbody td::before {
            content: attr(data-label);
            font-weight: bold;
            color: #6c757d;
            margin-right: 1rem;
            text-align: left;
        }
        
        /* Adjust images in card view */
        .table tbody td img {
            width: 32px; 
            height: 32px;
        }
    }

    /* Desktop Adjustments */
    @media (min-width: 768px) {
        .main-content {
            margin-left: 16.666667%; /* Match col-md-2 width */
        }
    }

    /* Hover Effect for Table Rows */
    .table tbody tr:hover {
        background-color: #f8f9fa;
        /* cursor: pointer; */ /* Only for clickable rows */
        transform: scale(1.005);
        transition: transform 0.2s ease;
    }

    /* Sidebar Backdrop */
    #sidebarBackdrop {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1030;
    }
    #sidebarBackdrop.show { display: block; }

    /* Welcome Box */
    .welcome-box {
        background: linear-gradient(135deg, #e8fdf1, #ffffff);
    }
    .cat-waving {
        max-height: 140px;
    }
    @media (max-width: 768px) {
        .welcome-box { text-align: center; }
    }
</style>
