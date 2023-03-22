@extends('layouts.layout-admin')
@section('styles')
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  <style>
    .dataTables_filter {
      margin-left: 10px;
      float: right;
    }
    .dataTables_info{
      float:left;
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
        <h1>{{ __('Product Categories') }}</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">{{ __('Home') }}</a></li>
          <li class="breadcrumb-item active">{{ __('Product Categories') }}</li>
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
                  <h3 class="card-title">{{ __('Manage Product Category') }}</h3>
                  <a href="{{ route('product-category.create') }}" style="float:right;" data-toggle="tooltip" title="Add" class="btn btn-primary btn-sm" data-toggle="tooltip" data-original-title="Add New">
                    <i class="fa fa-plus"></i> {{ __('Add New Product Category') }}
                </a>
                </div>
                <!-- /.card-header -->
              <div class="card-body"> 
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button> 
                        <strong>{{ $message }}</strong>
                    </div>
                    @endif
                    @if ($message = Session::get('failed') || $message = Session::get('error'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button> 
                        <strong>{{ $message }}</strong>
                    </div>
                    @endif                  
                    <table class="table table-bordered table-striped" id="product_category_table">
                        <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>   
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
              </div>
            </div>
        </div>
      </div>
    </div>
</section>
<!-- page end-->
@endsection
@section('scripts')

<!-- DataTables  & Plugins -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function(){
    $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });

    var table = $('#product_category_table').DataTable({
        processing: true,
        serverSide: true,        
        ajax: {
          url: "{{ route('product-category.index') }}",
          type: 'GET',
          data: function (d) {
            d.name = '';
          }
        },
        columns: [
            {data: 'name', name: 'name'},            
                 
            {data: 'action', name: 'action', orderable: false},
        ],
        order: [[0, 'asc']],
        responsive:true,
        lengthChange: false,
        autoWidth: false,
        dom: 'lfBrtip',        
        buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
        drawCallback: function( settings ) {
          $('[data-toggle="tooltip"]').tooltip(); // for tooltips in controls
          $('.deleteproductcategory').on('click',function(){
              var x = confirm("Do you want to delete the product category?");
              if(x == true) { 
              var productcategoryid = $(this).data('product-category'); 
                  $.ajax({
                      url: "{{ route('delete-product-category') }}",
                      data: { "id": productcategoryid },
                      type: "post",
                      dataType: "json",
                      success: function (data) {
                        window.location.reload();
                      }
                  });
              }
            });
          }        
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');    
});
</script>
@endsection