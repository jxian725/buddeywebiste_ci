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
        <h1>{{ __('Create Product Category') }}</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">{{ __('Home') }}</a></li>
          <li class="breadcrumb-item active">{{ __('Create Product Category') }}</li>
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
                  <h3 class="card-title">{{ __('Create Product Category') }}</h3>                  
                  <span class="tools" style="float:right">
                    <a href="{{ route('product-category.index') }}" title="Back" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i></a>
                  </span>
                </div>
                <!-- /.card-header -->
                <div class="card-body">  
                           
                
                <form id="product_category_add_form" autocomplete="off" role="form" enctype="multipart/form-data" method="POST" action="{{ route('product-category.store') }}">
                    @csrf                                          
                        <div class="form-group row @error('name') has-error @enderror">                                
                            <label for="name" class="control-label col-sm-4"> {{ __('Name') }} <span class="text-danger">*</span></label>
                            <div class="col-sm-5">    
                                <input type="text" class="form-control" name="name" id="name" placeholder="Product Category Name" required>
                                @if($errors->has('name'))
                                    <p class="text-danger">{{ $errors->first('name') }}</p>
                                @endif
                            </div>
                        </div>                        
                        <div class="form-group row @error('status') has-error @enderror">
                            <label for="status" class="control-label col-sm-4">{{ __('Status') }}<span class="text-danger">*</span></label>                                
                            <div class="col-sm-5">
                                {!! Form::select('status', $status, null, ['class' => 'form-control m-bot15']) !!}
                                @if($errors->has('status'))
                                    <p class="text-danger">{{ $errors->first('status') }}</p>
                                @endif
                            </div>
                        </div>
                        
                    <div class="form-group row" style="text-align:center;">
                        <div class="col-sm-12" >
                            <button type="submit" id="productCategoryAddBtn" class="btn btn-primary">{{ __('Add Product Category') }}</button>
                            <a href="{{ route('product-category.index') }}" class="btn btn-warning">{{ __('Cancel') }}</a>
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