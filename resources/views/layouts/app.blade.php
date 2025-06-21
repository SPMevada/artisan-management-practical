<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{env('APP_NAME')}} </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />
    <style>
        form .error {
            color: red; 
        }
        body {
            background-color: #fbfbfb;
        }
        @media (min-width: 991.98px) {
            main {
                padding-left: 240px;
            }
        }

        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            padding: 58px 0 0; /* Height of navbar */
            box-shadow: 0 2px 5px 0 rgb(0 0 0 / 5%), 0 2px 10px 0 rgb(0 0 0 / 5%);
            width: 240px;
            z-index: 600;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                width: 100%;
            }
        }
        .sidebar .active {
            border-radius: 5px;
            box-shadow: 0 2px 5px 0 rgb(0 0 0 / 16%), 0 2px 10px 0 rgb(0 0 0 / 12%);
        }

        .sidebar-sticky {
            position: relative;
            top: 0;
            height: calc(100vh - 48px);
            padding-top: 0.5rem;
            overflow-x: hidden;
            overflow-y: auto;
        }
    </style>
    @yield('header-css')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            @auth
                <header>
                    <nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse bg-white">
                        <div class="position-sticky">
                            <div class="list-group list-group-flush mx-3 mt-4">
                                <a href="{{ route('user.announcement') }}" class="list-group-item list-group-item-action py-2 ripple {{ request()->routeIs('user.announcement') ? 'active' : '' }}">
                                    <i class="fas fa-chart-area fa-fw me-3"></i><span>Announcements</span>
                                </a>
                                @if (Auth::user()->role == 'admin')
                                    <a href="{{ route('admin.teacher') }}" class="list-group-item list-group-item-action py-2 ripple {{ request()->routeIs('admin.teacher') ? 'active' : '' }}">
                                        <i class="fas fa-chart-pie fa-fw me-3"></i><span>Teachers</span>
                                    </a>
                                @endif
                                <a href="{{ route('student.index') }}" class="list-group-item list-group-item-action py-2 ripple {{ request()->routeIs('student.index') ? 'active' : '' }}">
                                    <i class="fas fa-chart-pie fa-fw me-3"></i><span>Students</span>
                                </a>
                                <a href="{{ route('parents.index') }}" class="list-group-item list-group-item-action py-2 ripple {{ request()->routeIs('parents.index') ? 'active' : '' }}">
                                    <i class="fas fa-chart-pie fa-fw me-3"></i><span>Parents</span>
                                </a>
                            </div>
                        </div>
                    </nav>
                    <nav id="main-navbar" class="navbar navbar-expand-lg bg-white fixed-top">
                        <div class="container-fluid">
                            <form action="{{ route('user.logout') }}" method="POST" >
                                @csrf
                                <button type="submit" class="list-group-item list-group-item-action py-2 ripple">
                                    <i class="fas fa-chart-pie fa-fw me-3"></i><span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </nav>
                </header>
                <main style="margin-top: 58px;">
                <div class="container pt-4"></div>
                </main>
            @endauth
            <main style="margin-top: 58px; margin-left: 240px;">
                @yield('content')
            </main>
        </div>
    </div>
    @yield('modal')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="{{ asset('js/common.js') }}"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
    @yield('script')
</body>
</html>