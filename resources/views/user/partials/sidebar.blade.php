<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar position-fixed collapse" role="navigation">
    <div class="d-flex flex-column h-100">
        {{-- Profile section --}}
        <div class="d-flex align-items-center p-4 border-bottom border-secondary border-opacity-25">
            <div class="position-relative">
                <img src="{{ Auth::user()->profile_image ? asset('storage/'.Auth::user()->profile_image) : asset('images/owner.png') }}"
                     class="rounded-circle border border-2 border-success shadow-sm" 
                     alt="Profile"
                     style="width:50px; height:50px; object-fit:cover;">
                <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
            </div>
            <div class="ms-3 overflow-hidden">
                <h6 class="fw-bold mb-0 text-white text-truncate">
                    {{ Auth::user()->username ?? Auth::user()->name ?? 'User' }}
                </h6>
                <small class="text-secondary">Pet Owner</small>
            </div>
        </div>

        {{-- Navigation --}}
        <div class="flex-grow-1 mt-4 px-3">
            <ul class="nav flex-column gap-2">
                {{-- Dashboard --}}
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('pet_owner.dashboard') ? 'active' : '' }}" 
                       href="{{ route('pet_owner.dashboard') }}">
                        <i class="bi bi-grid-fill me-3"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- Profile Settings --}}
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('pet_owner.edit') ? 'active' : '' }}" 
                       href="{{ route('pet_owner.edit') }}">
                        <i class="bi bi-person-circle me-3"></i>
                        <span>Profile Settings</span>
                    </a>
                </li>

                {{-- Interface Theme (Dropdown) --}}
                <li class="nav-item dropdown">
                    <a class="nav-link d-flex align-items-center dropdown-toggle" 
                       href="#" id="themeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-palette-fill me-3"></i>
                        <span>Interface Theme</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark border-0 shadow w-100 mt-1 rounded-3" aria-labelledby="themeDropdown">
                        <li><a class="dropdown-item d-flex align-items-center py-2" href="#" data-theme="light"><i class="bi bi-sun me-2"></i>Light Mode</a></li>
                        <li><a class="dropdown-item d-flex align-items-center py-2" href="#" data-theme="dark"><i class="bi bi-moon-stars me-2"></i>Dark Mode</a></li>
                    </ul>
                </li>

                {{-- Add New Pet --}}
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('pets.create') ? 'active' : '' }}" 
                       href="{{ route('pets.create') }}">
                        <i class="bi bi-plus-square-fill me-3"></i>
                        <span>Add New Pet</span>
                    </a>
                </li>

                {{-- Vet Notes --}}
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" 
                       href="#" data-bs-toggle="modal" data-bs-target="#vetNotesModal">
                        <i class="bi bi-journal-medical me-3"></i>
                        <span>Vet Notes</span>
                    </a>
                </li>
            </ul>
        </div>

        {{-- Bottom Section (Logout) --}}
        <div class="mt-auto p-3">
            <a class="nav-link d-flex align-items-center text-danger bg-danger bg-opacity-10 rounded-3 px-3 py-2" 
               href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="bi bi-box-arrow-right me-3"></i>
                <span class="fw-semibold">Log out</span>
            </a>
        </div>
    </div>
</nav>
