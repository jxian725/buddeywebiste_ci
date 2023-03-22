@extends('layouts.layout-admin')
@section('styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
.control-label{
    text-align: right;
}
.hide{
    display: none;
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
          <h1>{{ __('Edit Merchant') }}</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Edit Merchant') }}</li>
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
                    <h3 class="card-title">{{ __('Edit Merchant') }}</h3>                  
                    <span class="tools" style="float:right">
                      <a href="{{ route('merchant.index') }}" title="Back" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i></a>
                    </span>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body"> 
                       
                    <form class="form-horizontal" id="merchant_edit_form" autocomplete="off" role="form" enctype="multipart/form-data" method="POST" action="{{ route('merchant.update', $merchantInfo->id ) }}">
                        @csrf
                        {{method_field('PUT')}}
                        <div class="row">
                          <div class="col-sm-6">
                            <div class="form-group row @error('first_name') has-error @enderror">
                                <label for="first_name" class="col-sm-4 control-label">{{ __('First Name') }} <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="first_name" id="first_name" value="{{ $merchantInfo->first_name }}" placeholder="First Name">
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
                                    <input type="text" class="form-control" name="last_name" id="first_name" value="{{ $merchantInfo->last_name }}" placeholder="Last Name">
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
                                    <input type="email" class="form-control" name="email" id="email" value="{{$merchantInfo->email }}" placeholder="Email">
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
                                    <input type="text" class="form-control" name="address" id="address" value="{{ $merchantInfo->address }}" placeholder="Address">
                                    @if($errors->has('address'))
                                        <p class="text-danger">{{ $errors->first('address') }}</p>
                                    @endif
                                </div>
                            </div>
                          </div>
                          <div class="col-sm-6">
                              <div class="form-group row @error('phone') has-error @enderror">
                                <label for="phone" class="col-sm-4 control-label">{{ __('Phone Number') }}<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                  <input type="text" class="form-control" name="phone" id="phone" maxlength="13" value="{{ $merchantInfo->phone }}" placeholder="Example : 012 345 6789" />
                                  @if($errors->has('phone'))
                                        <p class="text-danger">{{ $errors->first('phone') }}</p>
                                    @endif
                                </div>
                            </div>
                          </div>
                          <div class="col-sm-6">      
                            <div class="form-group row @error('company_name') has-error @enderror">
                                <label for="company_name" class="control-label col-sm-4">{{ __('Company Name') }} <span class="text-danger">*</span></label></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="company_name" id="company_name" maxlength="13" value="{{ $merchantInfo->company_name }}" placeholder="Company Name" required />
                                    @if($errors->has('company_name'))
                                        <p class="text-danger">{{ $errors->first('company_name') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row @error('company_email') has-error @enderror">
                                <label for="company_email" class="control-label col-sm-4">{{ __('Company Email') }} <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="email" class="form-control" name="company_email" id="company_email" placeholder="Company Email" value="{{ $merchantInfo->company_email }}" required>
                                    @if($errors->has('company_email'))
                                        <p class="text-danger">{{ $errors->first('email') }}</p>
                                    @endif
                                </div>
                            </div>  
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row @error('company_phone') has-error @enderror">
                                <label for="company_phone" class="control-label col-sm-4">{{ __('Company Phone Number') }} <span class="text-danger">*</span></label></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="company_phone" id="company_phone" maxlength="13" value="{{ $merchantInfo->company_phone }}" placeholder="Example : 012 345 6789" required />
                                    @if($errors->has('company_phone'))
                                        <p class="text-danger">{{ $errors->first('company_phone') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row @error('product_category') has-error @enderror">
                                <label for="product_category" class="control-label col-md-4">{{ __('Product Category') }}<span class="text-danger">*</span></label>                                
                                <div class="col-md-8">
                                    <select id="product_category" name="product_category[]" data-placeholder="Select Product Category" class="form-control select2" multiple="multiple" value="{{ $merchantInfo->product_category }}" style="width: 100%;" required>
                                        <option value="0" selected> {{ __('all') }} </option>
                                        @foreach($product_category as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
         
                                    @if($errors->has('product_category'))
                                        <p class="text-danger">{{ $errors->first('product_category') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row @error('product_images') has-error @enderror ">
                                <label class="col-md-4 control-label">{{ __('Add Images') }}</label>
                                <div class="col-sm-8">
                                    <div class="input-group hdtuto control-group increment" >
                                        <input type="file" name="product_images[]" class="form-control" accept="image/*">
                                        <div class="input-group-btn"> 
                                          <button class="btn add-image btn-success" type="button"><i class="fldemo fa fa-plus"></i></button>
                                        </div>
                                      </div>
                                      <div class="clone hide">
                                        <div class="hdtuto control-group input-group" style="margin-top:10px">
                                          <input type="file" name="product_images[]" class=" form-control" accept="image/*">
                                          <div class="input-group-btn"> 
                                            <button class="btn btn-danger remove-image" type="button"><i class="fldemo fa fa-trash"></i> </button>
                                          </div>
                                        </div>
                                      </div>
                                      
                                      <input type='hidden' id="current_product_images" name="current_product_images" value="{{ $merchantInfo->product_images }}" />
                                      @if(isset($merchantInfo->product_images))
                                        @php $arrProductImages = explode('~',$merchantInfo->product_images) @endphp
                                        @foreach ($arrProductImages as $prod_image )
                                          <img src="{{ asset('uploads/product_images/'.$prod_image) }}" style="height:50px;width: auto;">
                                        @endforeach
                                      @endif
                                     
                                    @if($errors->has('product_images'))
                                        <p class="text-danger">{{ $errors->first('product_images') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row @error('site_address') has-error @enderror">
                                <label for="site_address" class="control-label col-sm-4">{{ __('Site Address') }} <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                <input type="text" class="form-control" name="site_address" id="site_address" placeholder="Site Address" value="{{ $merchantInfo->site_address }}" required/>
                                @if($errors->has('site_address'))
                                    <p class="text-danger">{{ $errors->first('site_address') }}</p>
                                @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row @error('branch_address') has-error @enderror">
                                <label for="branch_address" class="control-label col-sm-4">{{ __('Branch Address') }} <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                <input type="text" class="form-control" name="branch_address" id="branch_address" placeholder="Branch Address" value="{{ $merchantInfo->branch_address }}" required/>
                                @if($errors->has('branch_address'))
                                    <p class="text-danger">{{ $errors->first('branch_address') }}</p>
                                @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row @error('no_of_sales_person') has-error @enderror">
                                <label for="no_of_sales_person" class="control-label col-sm-4">{{ __('Number of SalesPerson') }} <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                <input type="text" class="form-control" name="no_of_sales_person" id="no_of_sales_person" placeholder="No of Sales_person" value="{{ $merchantInfo->no_of_sales_person }}" required/>
                                @if($errors->has('no_of_sales_person'))
                                    <p class="text-danger">{{ $errors->first('no_of_sales_person') }}</p>
                                @endif
                                </div>
                            </div>
                        </div>
                          
                          
                      <div class="col-sm-6">
                        <div class="form-group row @error('status') has-error @enderror">
                            <label for="status" class="control-label col-sm-4"> {{ __('Status') }} <span class="text-danger">*</span></label>                                
                            <div class="col-sm-8">
                              {!! Form::select('status', $status, $merchantInfo->status, ['class' => 'form-control m-bot15']) !!}
                                @if($errors->has('status'))
                                    <p class="text-danger">{{ $errors->first('status') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    </div>
                        <div class="form-group row" style="text-align: center;">
                            <div class="col-sm-12">
                                <button type="submit" id="merchantEditBtn" class="btn btn-primary">{{ __('Update Merchant') }}</button>
                                <a href="{{ route('merchant.index') }}" class="btn btn-warning">{{ __('Cancel') }}</a>
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
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
  $('.select2').select2()
 $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
    $(".add-image").click(function(){ 
          var lsthmtl = $(".clone").html();
          $(".increment").after(lsthmtl);
      });
      $("body").on("click",".remove-image",function(){ 
          $(this).parents(".hdtuto").remove();
      });

</script>
@endsection