@extends('layouts.layout-admin')

@section('content')
<!-- page start-->
 <!-- Content Header (Page header) -->
 <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>{{ __('Edit User') }}</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Edit User') }}</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <!-- Main content -->
  <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
              <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">{{ __('Edit Users') }}</h3>                  
                    <span class="tools" style="float:right">
                      <a href="{{ route('user.index') }}" title="Back" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i></a>
                    </span>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body"> 
                       
                    <form class="form-horizontal" id="user_edit_form" autocomplete="off" role="form" enctype="multipart/form-data" method="POST" action="{{ route('user.update', $userInfo->id ) }}">
                        @csrf
                        {{method_field('PUT')}}
                        
                        <div class="form-group @error('user_name') has-error @enderror">
                            <label for="user_name" class="col-lg-4 col-sm-4 control-label">{{ __('User Name') }} <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="user_name" id="user_name" value="{{ $userInfo->name }}" placeholder="Name" required>
                                 @if($errors->has('user_name'))
                                    <p class="text-danger">{{ $errors->first('user_name') }}</p>
                                 @endif
                            </div>
                        </div>
                        <div class="form-group @error('email') has-error @enderror">
                            <label for="email" class="col-lg-4 col-sm-4 control-label">{{ __('Email') }} <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="email" class="form-control" name="email" id="email" value="{{ $userInfo->email }}" placeholder="Email" required>
                                 @if($errors->has('email'))
                                    <p class="text-danger">{{ $errors->first('email') }}</p>
                                 @endif
                            </div>
                        </div>
                        {{-- <div class="form-group">
                            <label for="phone_number" class="col-lg-4 col-sm-4 control-label">Phone Number</label>
                            <div class="col-lg-8">
                                <div class="phone-list">
                                    <div class="input-group phone-input">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="type-text">{{$country_code}}</span></button>
                                        </span>
                                        <input type="hidden" name="country_code" id="country_code" class="type-input" value="{{$country_code}}" />
                                        <input type="text" class="form-control" name="phone_number" id="phone_number" maxlength="13" value="{{ $userInfo->phone_number }}" placeholder="Example : 012 345 6789" />
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        
                        <div class="form-group @error('role') has-error @enderror">
                            <label class="col-lg-4 col-sm-4 control-label" for="role">Role <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <select id="role" name="role" class="form-control" required>
                                    <option value="" disabled="disabled" selected> -- Select Role -- </option>
                                    @foreach($roles as $role)
                                        <option value="{{$role->name}}" @if($role->name == $userInfo->role) selected="selected" @endif>{{$role->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-lg-offset-4 col-lg-8">
                                <button type="submit" id="userEditBtn" class="btn btn-primary">{{ __('Update User') }}</button>
                                <a href="{{ route('user.index') }}" class="btn btn-warning">{{ __('Cancel') }}</a>
                            </div>
                        </div>
                    </form>
                  </div>
              </div>
          </div>
        </div>
      </div>
  </section>

<!-- page end-->
@endsection
@section('scripts')

@endsection