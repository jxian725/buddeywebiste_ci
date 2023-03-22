@extends('layouts.layout-admin')

@section('content')
<!-- page start-->
 <!-- Content Header (Page header) -->
 <section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>{{ __('Create Role') }}</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">{{ __('Home') }}</a></li>
          <li class="breadcrumb-item active">{{ __('Create Role') }}</li>
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
                  <h3 class="card-title">{{ __('Create Roles') }}</h3>                  
                  <span class="tools" style="float:right">
                    <a href="{{ route('role.index') }}" title="Back" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i></a>
                  </span>
                </div>
                <!-- /.card-header -->
                <div class="card-body">  
                           
                
                <form id="role_add_form" autocomplete="off" role="form" enctype="multipart/form-data" method="POST" action="{{ route('role.store') }}">
                    @csrf
                    <div class="form-group @error('role_name') has-error @enderror">
                        <label for="role_name" class="col-lg-4 col-sm-4 control-label"> {{ __('Role Name') }} <span class="text-danger">*</span></label>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="role_name" id="role_name" placeholder="Name" required>
                            @if($errors->has('role_name'))
                                <p class="text-danger">{{ $errors->first('role_name') }}</p>
                            @endif
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <div class="col-lg-3 custom-control custom-checkbox">           
                        {{ Form::checkbox('select_all',null,null, array('id'=>'select_all','class'=>'"select_all" custom-control-input')) }}
                        {{Form::label('select_all','Select All',['class'=>'custom-control-label'])}}<br>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        @php
                            $modules=['dashboard','customer','merchant','driver','order'];      
                        @endphp
                        <table border=1>
                          <thead>
                            <tr>
                            <th>Module Name</th>
                            <th>Permissions</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($modules as $module)
                            <tr>
                              <div class="row" id="sel_permissions">
                                <td class="col-sm-2 col-lg-2"> {{Form::label('module',ucfirst($module) )}} </td>                                
                                <td class="col-sm-10 col-lg-10">
                                  <div class="row" id="sel_permissions">
                                    @if(in_array('list '.$module,(array) $permissions))
                                      @if($key = array_search('list '.$module,$permissions))
                                      <div class="col-lg-2 col-sm-2 custom-control custom-checkbox">
                                        {{Form::checkbox('permissions[]','list '.$module,false, ['class'=>'checkbox custom-control-input','id' =>'permission'.$key])}}
                                        {{Form::label('permission'.$key,'List',['class'=>'custom-control-label'])}}<br>
                                      </div>
                                      @endif
                                    @endif
                                    @if(in_array('create '.$module,(array) $permissions))
                                        @if($key = array_search('create '.$module,$permissions))
                                            <div class="col-lg-2 col-sm-2 custom-control custom-checkbox">
                                                {{Form::checkbox('permissions[]','create '.$module,false, ['class'=>'checkbox custom-control-input','id' =>'permission'.$key])}}
                                                {{Form::label('permission'.$key,'Create',['class'=>'custom-control-label'])}}<br>
                                            </div>
                                        @endif
                                    @endif
                                    @if(in_array('edit '.$module,(array) $permissions))
                                        @if($key = array_search('edit '.$module,$permissions))
                                            <div class="col-lg-2 col-sm-2 custom-control custom-checkbox">
                                                {{Form::checkbox('permissions[]','edit '.$module,false, ['class'=>'checkbox custom-control-input','id' =>'permission'.$key])}}
                                                {{Form::label('permission'.$key,'Edit',['class'=>'custom-control-label'])}}<br>
                                            </div>
                                        @endif
                                    @endif  
                                    @if(in_array('delete '.$module,(array) $permissions))
                                        @if($key = array_search('delete '.$module,$permissions))
                                            <div class="col-lg-2 col-sm-2 custom-control custom-checkbox">
                                                {{Form::checkbox('permissions[]','delete '.$module,false, ['class'=>'checkbox custom-control-input','id' =>'permission'.$key])}}
                                                {{Form::label('permission'.$key,'Delete',['class'=>'custom-control-label'])}}<br>
                                            </div>
                                        @endif
                                    @endif  
                                  </div>                                               
                                </td>
                              
                            </tr> 
                            @endforeach
                          </tbody>
                        </table>
                      </div>                            
                
                    <div class="form-group">
                        <div class="col-lg-offset-4 col-lg-8">
                            <button type="submit" id="roleAddBtn" class="btn btn-primary">{{ __('Add Role') }}</button>
                            <a href="{{ route('role.index') }}" class="btn btn-warning">{{ __('Cancel') }}</a>
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
   $('#select_all').on('change', function() {     
                $('.checkbox').prop('checked', $(this).prop("checked"));        
        });
        $('.checkbox').change(function(){ //".checkbox" change 
            if($('.checkbox:checked').length == $('.checkbox').length){
                   $('#select_all').prop('checked',true);
            }else{
                   $('#select_all').prop('checked',false);
            }
        });
        $(document).ready(function() {
         if($('.checkbox:checked').length == $('.checkbox').length){
                   $('#select_all').prop('checked',true);
            }else{
                   $('#select_all').prop('checked',false);
            }
        });
</script>
@endsection