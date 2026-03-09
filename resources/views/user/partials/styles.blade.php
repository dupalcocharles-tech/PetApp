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
        transition: transform 0.3s ease-in-out;
        z-index: 1040;
        height: 100vh;
        box-shadow: 4px 0 24px 0 rgba(0,0,0,0.1);
    }

    .nav-link {
        color: #9ca3af; /* Gray-400 */
        transition: all 0.2s;
        border-radius: 8px;
        font-weight: 500;
        margin-bottom: 4px;
        padding: 0.75rem 1rem;
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

    .nav-link i {
        font-size: 1.25rem;
        width: 24px;
        text-align: center;
    }

    /* Mobile Responsive Sidebar */
    @media (max-width: 767.98px) {
        #sidebarMenu {
            position: fixed;
            top: 0;
            left: 0;
            width: 75%;
            max-width: 300px;
            transform: translateX(-100%);
        }

        #sidebarMenu.show {
            transform: translateX(0);
        }
    }
</style>
