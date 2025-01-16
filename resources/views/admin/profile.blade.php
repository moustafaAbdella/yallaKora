@extends('layouts.admin')

@section('content')
    <!-- End Navbar -->
    <div class="card shadow-lg mx-4">
      <div class="card-body p-3">
        <div class="row gx-4">
          <div class="col-auto">
            <div class="avatar avatar-xl position-relative">
              <img src="{{ asset('assets/img/user.png') }}" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
            </div>
          </div>
          <div class="col-auto my-auto">
            <div class="h-100">
              <h5 class="mb-1">
                {{ auth()->user()->name }}
              </h5>
              <p class="mb-0 font-weight-bold text-sm">
                {{ auth()->user()->role }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-md-12">
          <form method="POST" action="{{ route('profile.update') }}">
            @csrf
          <div class="card">
            <div class="card-header pb-0">
              <div class="d-flex align-items-center">
                <p class="mb-0">تعديل ملفي شخصي</p>
                <button class="btn btn-primary btn-sm me-auto">حفظ</button>
              </div>
            </div>
            <div class="card-body">
              <p class="text-uppercase text-sm"></p>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">اسم الكامل</label>
                    <input class="form-control" name="name" type="text" value="{{ auth()->user()->name }}">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">البريد الاكتروني</label>
                    <input class="form-control" name="email" type="email" value="{{ auth()->user()->email }}">
                  </div>
                </div>
              </div>
              
            </div>
          </div>
          </form>
        </div>
        <div class="col-md-12 mt-3">
          <form method="POST" action="{{ route('profile.password') }}">
            @csrf
          <div class="card">
            <div class="card-header pb-0">
              <div class="d-flex align-items-center">
                <p class="mb-0">تعديل كلمة سر</p>
                <button class="btn btn-primary btn-sm me-auto">حفظ</button>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">كلمة سر قديمة</label>
                    <input class="form-control" name="old_password" type="password" value="">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">كلمة سر جديدة</label>
                    <input class="form-control" name="password" type="password" value="">
                  </div>
                </div>
              </div>
            </div>
          </div>
          </form>
        </div>
      </div>

    </div>
@endsection
    
    
    