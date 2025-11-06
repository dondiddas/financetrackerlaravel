<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hover + Toggle Sidebar (Fixed for All Devices)</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
</head>

<body>
  <style></style>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <button class="btn d-lg-none" id="menu-toggle">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </nav>

    <div id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}"><i class="fa-solid fa-gauge"></i> Dashboard</a>
            </li>
                <li><a href="#"><i class="fa-solid fa-bolt"></i> Shortcut</a></li>
                <li><a href="#"><i class="fa-solid fa-cloud-arrow-down"></i> Overview</a></li>
                <li><a href="#"><i class="fa-solid fa-calendar"></i> Events</a></li>
                <li><a href="#"><i class="fa-brands fa-youtube"></i> About</a></li>
                <li><a href="#"><i class="fa-solid fa-wrench"></i> Services</a></li>
                <li><a href="#"><i class="fa-solid fa-server"></i> Contact</a></li>
            </ul>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Touch detection + toggle -->
    <script src="{{ asset('js/main.js') }}"></script>
</body>

</html>
