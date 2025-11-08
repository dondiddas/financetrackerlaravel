
<style>/* Default link styles */
#sidebar a {
    color: #fff;
    text-decoration: none;
    padding: 0.75rem 1rem;
    display: flex;
    align-items: center;
    gap: 14px;
    border-radius: 8px;
}

/* Icon default white */
#sidebar a i {
    color: #fff;
    font-size: 1.15rem;
}

/* Active link */
#sidebar li.active {
    background: #fff;
    border-radius: 8px;
}
#sidebar li.active a,
#sidebar li.active a i,
#sidebar li.active .link-text {
    color: #000 !important;
    font-weight: 700;
}

/* Hover */
#sidebar a:hover {
    background: rgba(255,255,255,0.18);
}

/* Sidebar base */
#sidebar {
    width: 70px;
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    background: #111;
    transition: width .3s ease;
    z-index: 2000;
    overflow-x: hidden;
    padding-top: 70px;
}

/* Desktop expand on hover */
@media (min-width: 768px) {
    #sidebar:hover {
        width: 240px;
    }

    #page-content-wrapper {
        margin-left: 70px;
        transition: margin-left .3s ease;
    }
    #sidebar:hover ~ #page-content-wrapper {
        margin-left: 240px;
    }
}

/* Text hidden by default */
#sidebar .link-text {
    opacity: 0;
    white-space: nowrap;
    transition: opacity .3s ease;
}

/* Text shows when expanded (desktop hover) */
@media (min-width: 768px) {
    #sidebar:hover .link-text {
        opacity: 1;
    }
}

/* Mobile behavior */
@media (max-width: 767px) {
    #sidebar {
        width: 180px;
        transform: translateX(-100%);
        transition: transform .3s ease-in-out;
    }
    #sidebar.active {
        transform: translateX(0);
    }

    /* When mobile menu opens, always show text */
    #sidebar.active .link-text {
        opacity: 1 !important;
    }

    #page-content-wrapper {
        margin-left: 0 !important;
    }
}
/* Hide the desktop sidebar in mobile */
@media (max-width: 767px) {
    #sidebar {
        display: none;
    }
}

/* Bottom nav */
.mobile-nav {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 60px;
    display: flex;
    justify-content: space-around;
    align-items: center;
    border-top: 1px solid rgba(255,255,255,0.2);
    z-index: 3000;
}

/* Nav item */
.mobile-nav .nav-item {
    flex: 1;
    text-align: center;
    color: #dcdcdc;
    font-size: 12px;
    padding-top: 5px;
}

/* Icons */
.mobile-nav .nav-item i{
    font-size: 18px;
    display: block;
    margin-bottom: 3px;
}

/* Active item */
.mobile-nav .nav-item.active,
.mobile-nav .nav-item:hover {
    color: #fff;
    font-weight: 600;
}



</style>
<div id="sidebar" class="bg-dark text-white">
    <ul class="sidebar-nav list-unstyled m-0 p-0">
        <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-4 px-3 py-2">
                <i class="fa-solid fa-gauge"></i> <span class="link-text">Dashboard</span>
            </a>
        </li>

        <li><a href="#" class="d-flex align-items-center gap-4  py-2"><i class="fa-solid fa-bolt"></i> <span class="link-text">Shortcut</span></a></li>
        <li><a href="#" class="d-flex align-items-center gap-4 py-2"><i class="fa-solid fa-cloud-arrow-down"></i> <span class="link-text">Overview</span></a></li>
        <li><a href="#" class="d-flex align-items-center gap-4  py-2"><i class="fa-solid fa-calendar"></i> <span class="link-text">Events</span></a></li>
        <li><a href="#" class="d-flex align-items-center gap-4  py-2"><i class="fa-brands fa-youtube"></i> <span class="link-text">About</span></a></li>
        <li><a href="#" class="d-flex align-items-center gap-4  py-2"><i class="fa-solid fa-wrench"></i> <span class="link-text">Services</span></a></li>
        <li><a href="#" class="d-flex align-items-center gap-4  py-2"><i class="fa-solid fa-server"></i> <span class="link-text">Contact</span></a></li>
    </ul>
</div>
<!-- Mobile Bottom Navbar -->
<nav id="mobileNav" class="mobile-nav bg-dark text-white d-md-none">
    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="fa-solid fa-gauge"></i>
        <span>Dashboard</span>
    </a>
    
    <a href="#" class="nav-item">
        <i class="fa-solid fa-bolt"></i>
        <span>Shortcut</span>
    </a>

    <a href="#" class="nav-item">
        <i class="fa-solid fa-cloud-arrow-down"></i>
        <span>Overview</span>
    </a>

    <a href="#" class="nav-item">
        <i class="fa-solid fa-calendar"></i>
        <span>Events</span>
    </a>

    <a href="#" class="nav-item">
        <i class="fa-solid fa-server"></i>
        <span>Contact</span>
    </a>
</nav>

