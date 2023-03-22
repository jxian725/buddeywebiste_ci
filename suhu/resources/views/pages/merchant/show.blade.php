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
          <h1>{{ __('View Merchant') }}</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">{{ __('View Merchant') }}</li>
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
    
                    <h3 class="profile-username text-center">{{ $merchantInfo->user_name }}</h3>
    
                    <p class="text-muted text-center">{{ $merchantInfo->company_name }}</p>
    
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                        <b>{{ __('Total Orders') }}</b> <a class="float-right">1,322</a>
                        </li>
                        <li class="list-group-item">
                        <b>{{ __('Total Customers') }}</b> <a class="float-right">543</a>
                        </li>
                        
                    </ul>
    
                    
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

            
            </div>
            <div class="col-md-9">
                <div class="card">
                  <div class="card-header p-2">
                    <h3 class="card-title">{{ __('Merchant Info') }}</h3>                  
                  <span class="tools" style="float:right">
                    <a href="{{ route('merchant.index') }}" title="Back" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i></a>
                  </span>
                  </div><!-- /.card-header -->
                  <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                        
                            <table class="table">
                              <tr>
                                <td>{{ __('Name') }}</td>
                                <td>{{ $merchantInfo->user_name }}</td>
                              </tr>
                              <tr>
                                <td>{{ __('Email') }}</td>
                                <td>{{  $merchantInfo->email  }}</td>
                              </tr>
                              <tr>
                                <td>{{ __('Address') }}</td>
                                <td>{{ $merchantInfo->address }}</td>
                              </tr>
                              <tr>
                                <td>{{ __('Phone') }}</td>
                                <td>{{ $merchantInfo->phone }}</td>
                              </tr>
                            
                              <tr>
                                <td>{{ __('Company Name') }}</td>
                                <td>{{ $merchantInfo->company_name }}</td>
                              </tr>
                              <tr>
                                <td>{{ __('Company Email') }}</td>
                                <td>{{ $merchantInfo->company_email}}</td>
                              </tr>
                              <tr>
                                <td>{{ __('Company Phone') }}</td>
                                <td>{{ $merchantInfo->company_phone }}</td>
                              </tr>
                              <tr>
                                <td>{{ __('Number of Salesperson') }}</td>
                                <td>{{ $merchantInfo->no_of_sales_person }}</td>
                              </tr>
                              
                            </table>
                        </div>
                    
                        <div class="col-sm-6 col-md-6">
                            <table class="table"> 
                                <tr>
                                    <td>{{ __('Product Category') }}</td>

                                    <td>
                                        @php
                                       
                                            $arrProdCat  = explode(',', $merchantInfo->product_category);
                                            if($arrProdCat){    
                                                if(count($arrProdCat)>1){
                                                    foreach($arrProdCat as $prodCat){
                                                        $productCategoryInfo = \App\Models\ProductCategory::where('id',$prodCat)->first();
                                                        if($productCategoryInfo){ echo $productCategoryInfo->name.', '; }
                                                    }
                                                }else{
                                                    $productCategoryInfo = \App\Models\ProductCategory::where('id',$merchantInfo->product_category)->first();
                                                    if($productCategoryInfo){ echo $productCategoryInfo->name; }
                                                }
                                            }
                                        
                                        @endphp
                                       
                                    </td>
                                </tr>                       
                                <tr>
                                    <td>{{ __('Commercial Address') }}</td>
                                    <td>{{ $merchantInfo->site_address }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Business Revenue Address') }}</td>
                                    <td>{{ $merchantInfo->branch_address }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Product Images') }}</td>
                                    <td>
                                        @php
                                             $arrProdImg  = explode('~', $merchantInfo->product_images);
                                            //  print_r($arrProdImg);
                                            if($arrProdImg){    
                                                if(count($arrProdImg)>1){
                                                    foreach($arrProdImg as $prodImg){
                                                        @endphp
                                                       <img src="{{ asset('uploads/product_images/'.$prodImg) }}" height="110px;" width="auto;"/>
                                                       
                                              @php
                                                    }                                              
                                              }
                                            }
                                        @endphp
                                       
                                            
                                       
                                    
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>{{ __('Status') }}</td>
                                    <td>{{ $status[$merchantInfo->status] }}</td>
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