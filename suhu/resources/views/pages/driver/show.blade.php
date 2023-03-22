@extends('layouts.layout-admin')
@section('style')
<style>


</style>
@endsection
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>{{ __('View Driver') }}</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">{{ __('View Driver') }}</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
</section>
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">               
                             <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                    <div class="text-center">
                        {{-- <i class="profile-user-img img-fluid img-circle fas fa-user-tie"></i> --}}
                        <img class="profile-user-img img-fluid img-circle"
                            src="{{ asset('dist/img/user4-128x128.jpg') }}"
                            alt="User profile picture">
                    </div>
    
                    <h3 class="profile-username text-center">{{ $driverInfo->user_name }}</h3>
    
                    <p class="text-muted text-center">{{ $driverInfo->company_name }}</p>
    
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                        <b>{{ __('Total Orders') }}</b> <a class="float-right">1,322</a>
                        </li>
                        {{-- <li class="list-group-item">
                        <b>{{ __('Total Customers') }}</b> <a class="float-right">543</a>
                        </li> --}}
                        
                    </ul>
    
                    
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

            
            </div>
            <div class="col-md-9">
                <div class="card">
                  <div class="card-header p-2">
                    <h3 class="card-title">{{ __('Driver Info') }}</h3>                  
                  <span class="tools" style="float:right">
                    <a href="{{ route('driver.index') }}" title="Back" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i></a>
                  </span>
                  </div><!-- /.card-header -->
                  <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                        
                            <table class="table">
                              <tr>
                                <td>{{ __('Name') }}</td>
                                <td>{{ $driverInfo->user_name }}</td>
                              </tr>
                              <tr>
                                <td>{{ __('Email') }}</td>
                                <td>{{  $driverInfo->email  }}</td>
                              </tr>
                              <tr>
                                <td>{{ __('Address') }}</td>
                                <td>{{ $driverInfo->address }}</td>
                              </tr>
                              <tr>
                                <td>{{ __('Phone') }}</td>
                                <td>{{ $driverInfo->phone }}</td>
                              </tr>
                                                       
                              
                            </table>
                        </div>
                    
                        <div class="col-sm-6 col-md-6">
                            <table class="table"> 
                                      
                               
                              <tr>
                                <td>{{ __('License Number') }}</td>
                                <td>{{ $driverInfo->license_number }}</td>
                              </tr>
                              <tr>
                                <td>{{ __('Car Plate Number') }}</td>
                                <td>{{ $driverInfo->car_plate_number }}</td>
                              </tr>
                              <tr>
                                <td>{{ __('Car Model Number') }}</td>
                                <td>{{ $driverInfo->car_model_number }}</td>
                              </tr>
                              <tr>
                                <td>{{ __('Road Tax Number') }}</td>
                                <td>{{ $driverInfo->road_tax_number }}</td>
                              </tr>
                              <tr>
                                <td>{{ __('Road Tax Expiry Date') }}</td>
                                <td>{{ $driverInfo->road_tax_expiry_date }}</td>
                              </tr>
                              <tr>
                                <td>{{ __('Insurance Number') }}</td>
                                <td>{{ $driverInfo->insurance_number }}</td>
                              </tr>
                              <tr>
                                <td>{{ __('Insurance Expiry Date') }}</td>
                                <td>{{ $driverInfo->insurance_expiry_date }}</td>
                              </tr>
                                
                                <tr>
                                    <td>{{ __('Status') }}</td>
                                    <td>{{ $status[$driverInfo->status] }}</td>
                                </tr>
                                
                            </table>
                          </div>                                                               
                               
                                
                            
                                                  
                        </div>
                    
                          <!-- /.col -->
                    </div>
                       
                       
                  </div><!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
              <!-- /.col -->
                                        
        </div>
    </div>
  </section>
                       
                    
@endsection
@section('scripts')
<script type="text/javascript">


</script>
@endsection