<nav class="pcoded-navbar menu-light ">
    <div class="navbar-wrapper  ">
        <div class="navbar-content scroll-div " >
            
            <div class="">
                <div class="main-menu-header">
                    <img class="img-radius" src="{{asset('template/dist/assets/images/user/avatar-2.jpg')}}" alt="User-Profile-Image">
                    <div class="user-details">
                        <div id="more-details">{{-- @auth --}}
                            {{-- {{ Auth::user()->username }} --}}
                        {{-- @endauth.  --}}<i class="fa fa-caret-down"></i></div>
                    </div>
                </div>
                <div class="collapse" id="nav-user-link">
                    <ul class="list-unstyled">
                        <li class="list-group-item">
                            <a href="{{ route('logout') }}">
                                <i class="feather icon-log-out m-r-5 text-danger"></i>
                                    Logout
                                </a>
                            </li>
                    </ul>
                </div>
            </div>
            
            <ul class="nav pcoded-inner-navbar ">
                <li class="nav-item pcoded-menu-caption">
                    <label>Navigation</label>
                </li>
                <li class="nav-item {{ Route::is('pages.dashboard') ? 'selected' : '' }}">
                    <a href="{{route('pages.dashboard')}}" 
                            class="nav-link ">
                        <span class="pcoded-micon">
                            <i class="feather icon-home"></i>
                        </span>
                        <span class="pcoded-mtext">
                            Dashboard
                        </span>
                    </a>
                </li>
                
                <li class="nav-item pcoded-menu-caption">
                    <label>Tabel</label>
                </li>

                <li class="nav-item {{ Route::is('pages.user') ? 'selected' : '' }}">
                    <a href="{{route('pages.user')}}" 
                            class="nav-link ">
                        <span class="pcoded-micon"><i class="feather icon-user"></i>
                        </span>
                        <span class="pcoded-mtext">
                            User
                        </span>
                    </a>
                </li>
                
                <li class="nav-item {{ Route::is('pages.departement') ? 'selected' : '' }}">
                    <a href="{{route('pages.departement')}}" 
                            class="nav-link ">
                        <span class="pcoded-micon"><i class="feather icon-briefcase"></i>
                        </span>
                        <span class="pcoded-mtext">
                            Departement
                        </span>
                    </a>
                </li>
                
                <li class="nav-item {{ Route::is('pages.jabatan') ? 'selected' : '' }}">
                    <a href="{{route('pages.jabatan')}}" 
                            class="nav-link ">
                        <span class="pcoded-micon"><i class="feather icon-circle"></i>
                        </span>
                        <span class="pcoded-mtext">
                            Jabatan
                        </span>
                    </a>
                </li>
                
                <li class="nav-item {{ Route::is('pages.koordinat') ? 'selected' : '' }}">
                    <a href="{{route('pages.koordinat')}}" 
                            class="nav-link ">
                        <span class="pcoded-micon">
                            <i class="feather icon-map">
                            </i>
                        </span>
                        <span class="pcoded-mtext">
                            Koordinat
                        </span>
                    </a>
                </li>

                <li class="nav-item {{ Route::is('pages.setup') ? 'selected' : '' }}">
                    <a href="{{route('pages.setup')}}" 
                            class="nav-link ">
                        <span class="pcoded-micon">
                            <i class="feather icon-settings">
                            </i>
                        </span>
                        <span class="pcoded-mtext">
                            Set Up
                        </span>
                    </a>
                </li>
                
                <li class="nav-item {{ Route::is('pages.gate') ? 'selected' : '' }}">
                    <a href="{{route('pages.gate')}}" 
                            class="nav-link ">
                        <span class="pcoded-micon">
                            <i class="feather icon-server">
                            </i>
                        </span>
                        <span class="pcoded-mtext">
                            Gate
                        </span>
                    </a>
                </li>
                
                <li class="nav-item {{ Route::is('pages.pegawai') ? 'selected' : '' }}">
                    <a href="{{route('pages.pegawai')}}" 
                            class="nav-link ">
                        <span class="pcoded-micon">
                            <i class="feather icon-users">
                            </i>
                        </span>
                        <span class="pcoded-mtext">
                            Pegawai
                        </span>
                    </a>
                </li>
                
                <li class="nav-item {{ Route::is('pages.kehadiran') ? 'selected' : '' }}">
                    <a href="{{route('pages.kehadiran')}}" 
                            class="nav-link ">
                        <span class="pcoded-micon">
                            <i class="feather icon-log-in">
                            </i>
                        </span>
                        <span class="pcoded-mtext">
                            Kehadiran
                        </span>
                    </a>
                </li>   
                
                <li class="nav-item {{ Route::is('pages.log') ? 'selected' : '' }}">
                    <a href="{{route('pages.log')}}" 
                            class="nav-link ">
                        <span class="pcoded-micon">
                            <i class="feather icon-package">
                            </i>
                        </span>
                        <span class="pcoded-mtext">
                            Log
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>