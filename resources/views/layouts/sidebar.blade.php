<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-end me-4 rotate-caret" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute start-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="/">
            <img src="{{ asset('/assets/img/logo-ct-dark.png') }}" class="navbar-brand-img h-100" alt="main_logo">
            <span class="me-1 font-weight-bold">يلا جول</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse px-0 w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{request()->routeIs('admin') ? 'active' : '' }}" href="{{route('admin')}}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center ms-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text me-1"> الرئسية</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{request()->routeIs('teams') ? 'active' : '' }}" href="{{route('teams')}}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center ms-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-spaceship text-warning text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text me-1">الفرق</span>
                </a>
            </li>
            <li class="nav-item" >
                <a class="nav-link {{request()->routeIs('leagues') ? 'active' : '' }}" href="{{route('leagues')}}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center ms-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-trophy text-success text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text me-1">البطولات</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{request()->routeIs('matches') ? 'active' : '' }}" href="{{route('matches')}}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center ms-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-calendar-grid-58 text-warning text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text me-1">المباريات</span>
                </a>
            </li>
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#livetv"
                   class="nav-link collapsed {{  ( request()->routeIs('livetv') || request()->routeIs('categories') )  ? 'active' : '' }}"
                   aria-controls="livetv" role="button" aria-expanded="false">
                    <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                        <i class="ni ni-tablet-button text-secondary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1"> القنوات </span>
                </a>
                <div class="collapse" id="livetv" style="">
                    <ul class="nav ms-4">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('livetv') ? 'active' : '' }}" href="{{ route('livetv') }}">
                                <span class="sidenav-mini-icon"> D </span>
                                <span class="sidenav-normal"> القنوات </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link {{ request()->routeIs('categories') ? 'active' : '' }}" href="{{ route('categories') }}">
                                <span class="sidenav-mini-icon"> D </span>
                                <span class="sidenav-normal"> فئات </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link {{request()->routeIs('servers') ? 'active' : '' }}" href="{{route('servers')}}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center ms-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-atom text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text me-1">سيرفرات</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{request()->routeIs('notifications') ? 'active' : '' }}" href="{{route('notifications')}}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center ms-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-send text-danger text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text me-1">الاشعاراات</span>
                </a>
            </li>
            <li class="nav-item" >
                <a class="nav-link {{request()->routeIs('users') ? 'active' : '' }}" href="{{route('users')}}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center ms-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text me-1">المستخدمين</span>
                </a>
            </li>
            <li class="nav-item" >
                <a class="nav-link {{request()->routeIs('subscriptions') ? 'active' : '' }}" href="{{route('subscriptions')}}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center ms-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tie-bow text-info text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text me-1">الاشتركات</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{request()->routeIs('ad.tracks') ? 'active' : '' }}" href="{{route('ad.tracks')}}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center ms-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-world-2 text-danger text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text me-1">تتبع الاعلانات</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{request()->routeIs('settinges') ? 'active' : '' }}" href="{{route('settinges')}}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center ms-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-world-2 text-danger text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text me-1">الاعدادات</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{request()->routeIs('player.settings') ? 'active' : '' }}" href="{{route('player.settings')}}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center ms-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-world-2 text-danger text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text me-1">اعدادات البلاير</span>
                </a>
            </li>
        </ul>
    </div>

</aside>
