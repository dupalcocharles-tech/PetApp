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
                        <h2 class="fw-bold text-success mb-1"><i class="bi bi-paw me-2"></i>Manage Animals</h2>
                        <p class="text-muted mb-0">Select the animals your clinic is equipped to handle.</p>
                    </div>
                    <a href="{{ route('clinic.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-arrow-left me-2"></i> Back to Dashboard
                    </a>
                </div>

                {{-- Animals Form --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-4 border-success rounded-3" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('clinic.animals.update') }}" method="POST">
                            @csrf
                            
                            <p class="text-muted small mb-4">Check all that apply. This helps pet owners find your clinic.</p>

                            <div class="row g-3">
                                @php
                                    $animalList = ['Dogs','Cats','Cows','Sheep','Goats','Pigs','Horses','Rabbits','Chickens','Ducks','Turkeys','Geese','Parrots','Hamsters','Guinea Pigs','Mice','Rats'];
                                @endphp

                                @foreach($animalList as $index => $animal)
                                    <div class="col-6 col-sm-4 col-md-3">
                                        <input type="checkbox" class="btn-check" id="animal_{{ $index }}" 
                                               name="specializations[]" value="{{ $animal }}"
                                               {{ is_array($clinicAnimals) && in_array($animal, $clinicAnimals) ? 'checked' : '' }}>
                                        <label class="btn btn-outline-success w-100 d-flex align-items-center justify-content-center gap-2 py-3 rounded-4 shadow-sm h-100" 
                                               for="animal_{{ $index }}">
                                            @if($animal == 'Dogs') <i class="bi bi-emoji-smile"></i>
                                            @elseif($animal == 'Cats') <i class="bi bi-emoji-wink"></i>
                                            @else <i class="bi bi-circle-fill small text-opacity-50" style="font-size: 0.5rem;"></i>
                                            @endif
                                            {{ $animal }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-5 text-center">
                                <button type="submit" class="btn btn-success fw-bold rounded-pill px-5 py-3 shadow hover-scale">
                                    <i class="bi bi-save me-2"></i> Update Specializations
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

@include('staff.partials.scripts')
@endsection
