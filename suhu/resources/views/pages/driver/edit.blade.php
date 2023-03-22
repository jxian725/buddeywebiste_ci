@extends('layouts.layout-admin')
@section('styles')
<style>
.control-label{
    text-align: right;
}
</style>
@endsection
@section('content')
<!-- page start-->
 <!-- Content Header (Page header) -->
 <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>{{ __('Edit Driver') }}</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Edit Driver') }}</li>
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
                    <h3 class="card-title">{{ __('Edit Driver') }}</h3>                  
                    <span class="tools" style="float:right">
                      <a href="{{ route('driver.index') }}" title="Back" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i></a>
                    </span>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body"> 
                       
                    <form class="form-horizontal" id="driver_edit_form" autocomplete="off" role="form" enctype="multipart/form-data" method="POST" action="{{ route('driver.update', $driverInfo->id ) }}">
                        @csrf
                        {{method_field('PUT')}}
                        <div class="row">
                          <div class="col-sm-6">
                            <div class="form-group row @error('first_name') has-error @enderror">
                                <label for="first_name" class="col-sm-4 control-label">{{ __('First Name') }} <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="first_name" id="first_name" value="{{ $driverInfo->first_name }}" placeholder="First Name">
                                    @if($errors->has('first_name'))
                                        <p class="text-danger">{{ $errors->first('first_name') }}</p>
                                    @endif
                                </div>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group row @error('last_name') has-error @enderror">
                                <label for="last_name" class="col-sm-4 control-label">{{ __('Last Name') }} <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="last_name" id="first_name" value="{{ $driverInfo->last_name }}" placeholder="Last Name">
                                    @if($errors->has('last_name'))
                                        <p class="text-danger">{{ $errors->first('last_name') }}</p>
                                    @endif
                                </div>
                            </div>
                          </div>
                          
                          <div class="col-sm-6">
                            <div class="form-group row @error('email') has-error @enderror">
                                <label for="email" class="col-sm-4 control-label">{{ __('Email') }} <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="email" class="form-control" name="email" id="email" value="{{ $driverInfo->email }}" placeholder="Email">
                                    @if($errors->has('email'))
                                        <p class="text-danger">{{ $errors->first('email') }}</p>
                                    @endif
                                </div>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group row @error('address') has-error @enderror">
                                <label for="address" class="col-sm-4 control-label">{{ __('Address') }} <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="address" id="address" value="{{ $driverInfo->address }}" placeholder="Address">
                                    @if($errors->has('address'))
                                        <p class="text-danger">{{ $errors->first('address') }}</p>
                                    @endif
                                </div>
                            </div>
                          </div>
                          <div class="col-sm-6">
                              <div class="form-group row @error('phone') has-error @enderror">
                                <label for="phone" class="col-sm-4 control-label">Phone Number<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                  <input type="text" class="form-control" name="phone" id="phone" maxlength="13" value="{{ $driverInfo->phone }}" placeholder="Example : 012 345 6789" />
                                  @if($errors->has('phone'))
                                        <p class="text-danger">{{ $errors->first('phone') }}</p>
                                    @endif
                                </div>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group row @error('license_number') has-error @enderror">
                              <label for="license_number" class="col-sm-4 control-label">License Number<span class="text-danger">*</span></label>
                              <div class="col-sm-8">
                                <input type="text" class="form-control" name="license_number" id="license_number" maxlength="13" value="{{ $driverInfo->license_number }}" placeholder="Licence Number" />
                                @if($errors->has('license_number'))
                                      <p class="text-danger">{{ $errors->first('license_number') }}</p>
                                  @endif
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group row @error('car_plate_number') has-error @enderror">
                              <label for="car_plate_number" class="col-sm-4 control-label">Car Plate Number<span class="text-danger">*</span></label>
                              <div class="col-sm-8">
                                <input type="text" class="form-control" name="car_plate_number" id="car_plate_number" maxlength="13" value="{{ $driverInfo->car_plate_number }}" placeholder="Car Plate Number" />
                                @if($errors->has('car_plate_number'))
                                      <p class="text-danger">{{ $errors->first('car_plate_number') }}</p>
                                  @endif
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group row @error('car_model_number') has-error @enderror">
                              <label for="car_model_number" class="col-sm-4 control-label">Car Model Number<span class="text-danger">*</span></label>
                              <div class="col-sm-8">
                                <input type="text" class="form-control" name="car_model_number" id="car_model_number" maxlength="13" value="{{ $driverInfo->car_model_number }}" placeholder="Car Model Number" />
                                @if($errors->has('car_model_number'))
                                      <p class="text-danger">{{ $errors->first('car_model_number') }}</p>
                                  @endif
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group row @error('road_tax_number') has-error @enderror">
                              <label for="road_tax_number" class="col-sm-4 control-label">Road Tax Number<span class="text-danger">*</span></label>
                              <div class="col-sm-8">
                                <input type="text" class="form-control" name="road_tax_number" id="road_tax_number" maxlength="13" value="{{ $driverInfo->road_tax_number }}" placeholder="Road Tax Number" />
                                @if($errors->has('road_tax_number'))
                                      <p class="text-danger">{{ $errors->first('road_tax_number') }}</p>
                                  @endif
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-6">
                            <div class="form-group row @error('road_tax_expiry_date') has-error @enderror">
                                <label for="road_tax_expiry_date" class="control-label col-sm-4">Road Tax Expiry Date<span class="text-danger">*</span></label>
                                {{-- <input type="text" class="datepicker form-control col-sm-8" name="road_tax_expiry_date" id="road_tax_expiry_date" value="{{old('road_tax_expiry_date')}}" placeholder="Road Tax Expiry Date" required/> --}}
                                <div class="input-group col-sm-8 date">
                                    <input type="text" class="form-control  datetimepicker-input" id="road_tax_expiry_date" name="road_tax_expiry_date" data-target-input="nearest" value="{{ $driverInfo->road_tax_expiry_date }}" placeholder="Road Tax Expiry Date" required/>
                                    <div class="input-group-append" data-target="#road_tax_expiry_date" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    @if($errors->has('road_tax_expiry_date'))
                                        <p class="text-danger">{{ $errors->first('road_tax_expiry_date') }}</p>
                                    @endif  
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group row @error('insurance_number') has-error @enderror">
                              <label for="insurance_number" class="control-label col-sm-4">Insurance Number <span class="text-danger">*</span></label>                                
                              <div class="col-sm-8">
                                  <input type="text" class="form-control" name="insurance_number" id="insurance_number" maxlength="13" value="{{$driverInfo->insurance_number}}" placeholder="Insurance Number" required />
                                  @if($errors->has('insurance_number'))
                                      <p class="text-danger">{{ $errors->first('insurance_number') }}</p>
                                  @endif
                              </div>
                          </div>
                      </div>
                      <div class="col-lg-6">
                          <div class="form-group row @error('insurance_expiry_date') has-error @enderror">
                              <label for="insurance_expiry_date" class="control-label col-sm-4">Insurance Expiry Date<span class="text-danger">*</span></label>
                              {{-- <input type="text" class="datepicker form-control col-sm-8" name="insurance_expiry_date" id="insurance_expiry_date" value="{{old('insurance_expiry_date')}}" placeholder="Insurance Expiry Date" required/> --}}
                              <div class="input-group col-sm-8 date" >
                                  <input type="text" class="form-control  datetimepicker-input" name="insurance_expiry_date" id="insurance_expiry_date" data-target-input="nearest" value="{{$driverInfo->insurance_expiry_date}}" placeholder="Insurance Expiry Date" required/>
                                  <div class="input-group-append" data-target="#insurance_expiry_date" data-toggle="datetimepicker">
                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                  </div>
                              </div>
                              @if($errors->has('insurance_expiry_date'))
                                  <p class="text-danger">{{ $errors->first('insurance_expiry_date') }}</p>
                              @endif
                              
                          </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group row @error('status') has-error @enderror">
                            <label for="status" class="control-label col-sm-4">Status <span class="text-danger">*</span></label>                                
                            <div class="col-sm-8">
                              {!! Form::select('status', $status, $driverInfo->status, ['class' => 'form-control m-bot15']) !!}
                                @if($errors->has('status'))
                                    <p class="text-danger">{{ $errors->first('status') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    </div>
                        <div class="form-group row" style="text-align: center;">
                            <div class="col-sm-12">
                                <button type="submit" id="driverEditBtn" class="btn btn-primary">{{ __('Update Driver') }}</button>
                                <a href="{{ route('driver.index') }}" class="btn btn-warning">{{ __('Cancel') }}</a>
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
<script>
//Date picker
$('#insurance_expiry_date,#road_tax_expiry_date').datetimepicker({
    startDate: '{{date("Y-m-d H:i:s")}}',
    format: 'YYYY-MM-DD',
    autoclose: true
});
</script>
@endsection