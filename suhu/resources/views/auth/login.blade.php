@extends('layouts.layout-app')

@section('content')
<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="{{ route('login') }}" class="h1"><b>{{ config('app.name', 'Laravel') }}</b></a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">{{ __('Sign in to start your session') }}</p>
  
        <form action="{{ route('login') }}" method="post">
        @csrf
          <div class="input-group mb-3">
            <input id="email" type="email" placeholder="Email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
          <div class="input-group mb-3">
            <input id="password" type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
          <div class="row">
            <div class="col-8">
              <div class="icheck-primary">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">
                    {{ __('Remember Me') }}
                </label>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">{{ __('Login') }}</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
        <hr>
        <div class="row">
            <div class="col-8">

                <p class="mb-1">
                <a href="{{ route('password.request') }}">{{ __('Forgot Password') }}</a>
                </p>
            </div>
            <div class="col-4">
                <p class="mb-0">
                <a href="{{ route('register') }}" class="text-center">{{ __('Register') }}</a>
                </p>
            </div>
        </div>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.login-box -->
  
@endsection
