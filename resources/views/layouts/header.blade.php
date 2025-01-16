<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
    <div class="container-fluid py-1 px-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 ">
          <li class="breadcrumb-item text-sm ps-2"><a class="opacity-5 text-white" href="javascript:;">لوحة تحكم </a></li>
          {{-- <li class="breadcrumb-item text-sm text-white active" aria-current="page">RTL</li> --}}
        </ol>
        {{-- <h6 class="font-weight-bolder text-white mb-0">RTL</h6> --}}
      </nav>
      <div class="collapse navbar-collapse mt-sm-0 mt-2 px-0" id="navbar">

        <ul class="navbar-nav me-auto ms-0 justify-content-end">
          <li class="nav-item dropdown">
            <a class="nav-link pr-0" href="javascript:;" role="button"id="dropdownMenuButton" data-bs-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
                <div class="media align-items-center">
                    <span class="avatar avatar-sm rounded-circle">
                        <img alt="Image placeholder" src="{{ asset('assets/img/user.png') }}">
                    </span>
                    <div class="media-body ml-2 d-none d-lg-block">
                        <span class="mb-0 text-sm  font-weight-bold">{{ auth()->user()->name }}</span>
                    </div>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right p-1" aria-labelledby="dropdownMenuButton">
                <div class=" dropdown-header noti-title">
                    <h6 class="text-overflow m-0">{{ __('Welcome!') }}</h6>
                </div>
                <a class="dropdown-item border-radius-md" href="{{ route('profile') }}">
                  <div class="d-flex py-1">
                    <div class="my-auto">
                  <i class="ni ni-support-16"></i></div>                    <div class="d-flex flex-column justify-content-center me-2">
                    <h6 class="text-sm font-weight-normal mb-1">{{ __(' ملف شخصي ') }}</h6>
                    </div>
                  </div>
                </a>
                <a class="dropdown-item border-radius-md" href="{{ route('logout') }}"  onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                  
                                  <div class="d-flex py-1">
                                    <div class="my-auto">
                    <i class="ni ni-user-run"></i>
                  </div>                    <div class="d-flex flex-column justify-content-center me-2">
                    <h6 class="text-sm font-weight-normal mb-1">{{ __(' تسجيل خروج ') }}</h6>
                  </div>
                </div>
                </a>
            </div>
        </li>

        </ul>
      </div>
    </div>
</nav>