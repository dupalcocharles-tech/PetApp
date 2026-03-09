@extends('layouts.app')

@section('content')
@include('staff.partials.styles')

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
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 main-content bg-light min-vh-100">
            <div class="container-xl">
                
                {{-- Header --}}
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 bg-white p-4 rounded-4 shadow-sm border-start border-5 border-success">
                    <div class="mb-3 mb-md-0">
                        <h2 class="fw-bold text-success mb-1"><i class="bi bi-clipboard-heart me-2"></i>Clinic Animals</h2>
                        <p class="text-muted mb-0">Manage the list of animals your clinic specializes in.</p>
                    </div>
                    <div>
                         <a href="{{ route('staff.edit') }}" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">
                            <i class="bi bi-pencil-square me-2"></i>Manage Animals
                        </a>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        
                        {{-- Animals Card --}}
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-white border-bottom border-light p-3">
                                <h5 class="fw-bold text-success mb-0"><i class="bi bi-paw me-2"></i>Specializations</h5>
                            </div>
                            <div class="card-body p-4">
                                @if(empty($animals) || count($animals) === 0)
                                    <div class="text-center py-5">
                                        <div class="mb-3">
                                            <i class="bi bi-paw text-muted" style="font-size: 3rem;"></i>
                                        </div>
                                        <h5 class="text-muted">No animals listed</h5>
                                        <p class="text-muted mb-4">Your clinic hasn't selected any animal specializations yet.</p>
                                        <a href="{{ route('staff.edit') }}" class="btn btn-outline-success rounded-pill px-4">
                                            Add Specializations
                                        </a>
                                    </div>
                                @else
                                    <div class="row g-3">
                                        @foreach($animals as $animal)
                                            <div class="col-6 col-sm-4 col-md-3">
                                                <div class="card h-100 border-success border-opacity-25 bg-success bg-opacity-10 shadow-sm text-center p-3 rounded-4">
                                                    <div class="mb-2">
                                                        @if($animal == 'Dogs') <i class="bi bi-emoji-smile fs-1 text-success"></i>
                                                        @elseif($animal == 'Cats') <i class="bi bi-emoji-wink fs-1 text-success"></i>
                                                        @elseif($animal == 'Birds' || $animal == 'Parrots') <i class="bi bi-twitter fs-1 text-success"></i>
                                                        @else <i class="bi bi-circle-fill text-success" style="font-size: 1rem;"></i>
                                                        @endif
                                                    </div>
                                                    <h5 class="fw-bold text-dark mb-0">{{ $animal }}</h5>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

@include('staff.partials.scripts')
@endsection
