@extends('layouts.app')

@section('content')
{{-- Internal Styles for Self-Contained Design --}}
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
        background-image: radial-gradient(#e5e7eb 1px, transparent 1px);
        background-size: 20px 20px;
        color: var(--text-dark);
        min-height: 100vh;
    }

    /* Sidebar Styling */
    #sidebarMenu {
        background: linear-gradient(180deg, #111827 0%, #1f2937 100%);
        box-shadow: 4px 0 24px rgba(0,0,0,0.1);
        z-index: 1040;
        height: 100vh;
        border-right: 1px solid rgba(255,255,255,0.05);
    }

    .nav-link {
        color: #9ca3af;
        border-radius: 12px;
        margin-bottom: 4px;
        padding: 12px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .nav-link:hover {
        color: #fff;
        background: rgba(255, 255, 255, 0.05);
        transform: translateX(4px);
    }

    .nav-link.active {
        background: linear-gradient(90deg, var(--primary-green) 0%, var(--primary-dark) 100%);
        color: #fff;
        box-shadow: 0 4px 12px rgba(13, 148, 136, 0.3);
    }

    /* Cards */
    .card {
        background: var(--card-bg);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 24px;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.1);
    }

    /* Mobile adjustments */
    @media (max-width: 767.98px) {
        .main-content {
            padding-top: 80px;
        }
        
        #sidebarMenu {
            width: 280px;
            background: #1f2937;
            position: fixed;
            left: -280px;
            transition: left 0.3s ease;
        }
        
        #sidebarMenu.show {
            left: 0;
        }
    }
    
    #sidebarBackdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1030;
        display: none;
        backdrop-filter: blur(2px);
    }
    
    #sidebarBackdrop.show {
        display: block;
    }

    /* Scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
    }
    ::-webkit-scrollbar-track {
        background: transparent;
    }
    ::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }

    /* Dark Theme Support */
    body.dark-theme .card {
        background-color: #1e1e1e;
        color: #e0e0e0;
        border-color: #333;
    }
    body.dark-theme .text-dark {
        color: #e0e0e0 !important;
    }
    body.dark-theme .text-muted {
        color: #adb5bd !important;
    }
</style>

<div class="container-fluid p-0 m-0">
    {{-- Mobile Sidebar Toggle --}}
    <button class="btn btn-success d-md-none position-fixed top-0 start-0 m-3 z-3 shadow rounded-circle p-2" 
            id="sidebarToggle" style="width: 45px; height: 45px;">
        <i class="bi bi-list fs-4 text-white"></i>
    </button>

    {{-- Sidebar Backdrop --}}
    <div id="sidebarBackdrop"></div>

    <div class="row g-0">
        {{-- Sidebar --}}
        @include('staff.partials.sidebar')

        {{-- Main Content --}}
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 main-content">
             <div class="d-flex justify-content-between align-items-center mb-4 mt-5 mt-md-0">
                <h4 class="fw-bold mb-0" style="color: var(--primary-dark);">Clinic Reviews</h4>
                <div class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm fs-6">
                    <i class="bi bi-star-fill me-1"></i> {{ $clinic->average_rating }} / 5.0
                </div>
            </div>

            <div class="row g-4">
                @forelse($reviews as $review)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4 review-card overflow-hidden">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                        <span class="fw-bold text-muted">{{ substr($review->owner->full_name ?? 'A', 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0">{{ $review->owner->full_name ?? 'Anonymous' }}</h6>
                                        <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning"></i>
                                    @endfor
                                </div>
                                
                                <p class="text-secondary mb-0">
                                    {{ $review->review ?? 'No comment provided.' }}
                                </p>

                                @if(!empty($review->images))
                                    <div class="mt-3 d-flex gap-2 overflow-auto custom-scrollbar pb-2">
                                        @foreach($review->images as $img)
                                             <img src="{{ asset('storage/reviews/' . $img) }}" class="rounded-3 border" style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;" onclick="window.open(this.src, '_blank')">
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="mb-3 opacity-50">
                            <i class="bi bi-chat-square-quote display-1 text-muted"></i>
                        </div>
                        <h5 class="text-muted fw-medium">No reviews yet</h5>
                        <p class="text-secondary small">Your clinic hasn't received any reviews yet.</p>
                    </div>
                @endforelse
            </div>
            
            <div class="mt-4">
                {{ $reviews->links() }}
            </div>
        </main>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function(){
        // Sidebar Toggle Logic
        const sidebar = document.getElementById('sidebarMenu');
        const toggleBtn = document.getElementById('sidebarToggle');
        const backdrop = document.getElementById('sidebarBackdrop');
    
        if(toggleBtn){
            toggleBtn.addEventListener('click', function(){
                sidebar.classList.toggle('show');
                backdrop.classList.toggle('show');
            });
        }
    
        if(backdrop){
            backdrop.addEventListener('click', function(){
                sidebar.classList.remove('show');
                backdrop.classList.remove('show');
            });
        }
    });
</script>
@endsection

@endsection