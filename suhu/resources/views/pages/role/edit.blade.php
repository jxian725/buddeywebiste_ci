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
          <li class="breadcrumb-item active">{{ __('Update Role') }}</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title">{{ __('Update Roles') }}</h3>                  
                  <span class="tools" style="float:right">
                    <a href="{{ route('role.index') }}" title="Back" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i></a>
                  </span>
                </div>
                <!-- /.card-header -->
                <div class="card-body"> 
                    <form class="form-horizontal" autocomplete="off" role="form" method="POST" action="{{ route('role.update', $roleInfo->id) }}">
                        @csrf
                        {{method_field('PUT')}}   
                       <div class="form-group @error('name') has-error @enderror">
                           <label for="name" class="col-lg-4 col-sm-4 control-label">Role Name <span class="text-danger">*</span></label>
                           <div class="col-lg-8">
                               <input type="text" class="form-control" name="name" value="{{ $roleInfo->name }}" id="name" placeholder="role Name" required>
                                @if($errors->has('name'))
                                   <p class="help-block">{{ $errors->first('name') }}</p>
                                @endif
                           </div>
                       </div>
                       <div class="col-md-12">
                           <div class="form-group">
                               <div class="col-md-3 custom-control custom-checkbox">           
                               {{ Form::checkbox('select_all',null,null, array('id'=>'select_all','class'=>'class="select_all"  custom-control-input')) }}
                               {{Form::label('select_all','Select All',['class'=>'custom-control-label'])}}<br>
                               </div>
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
                                
                                 <td class="col-sm-2 col-lg-2"> {{Form::label('module',ucfirst($module))}} </td>
                                 <td class="col-sm-10 col-lg-10">
                                   <div class="row" id="sel_permissions">
                                       
                                       @if(in_array('list '.$module,(array) $permissions))
                                        @if($key = array_search('list '.$module,$permissions))
                                            <div class="col-lg-2 col-sm-2 custom-control custom-checkbox">
                                            {{Form::checkbox('permissions[]','list '.$module,in_array('list '.$module,$assignedPermission), ['class'=>'checkbox custom-control-input','id' =>'permission'.$key])}}
                                            {{Form::label('permission'.$key,'List',['class'=>'custom-control-label'])}}<br>
                                            </div>
                                            @endif
                                        @endif
                                   @if(in_array('create '.$module,(array) $permissions))
                                       @if($key = array_search('create '.$module,$permissions))
                                           <div class="col-lg-2 col-sm-2 custom-control custom-checkbox">
                                               {{Form::checkbox('permissions[]','create '.$module,in_array('create '.$module,$assignedPermission), ['class'=>'checkbox custom-control-input','id' =>'permission'.$key])}}
                                               {{Form::label('permission'.$key,'Create',['class'=>'custom-control-label'])}}<br>
                                           </div>
                                       @endif
                                   @endif
                                   @if(in_array('edit '.$module,(array) $permissions))
                                       @if($key = array_search('edit '.$module,$permissions))
                                           <div class="col-lg-2 col-sm-2 custom-control custom-checkbox">
                                               {{Form::checkbox('permissions[]','edit '.$module,in_array('edit '.$module,$assignedPermission), ['class'=>'checkbox custom-control-input','id' =>'permission'.$key])}}
                                               {{Form::label('permission'.$key,'Edit',['class'=>'custom-control-label'])}}<br>
                                           </div>
                                       @endif
                                   @endif    
                                   @if(in_array('delete '.$module,(array) $permissions))
                                       @if($key = array_search('delete '.$module,$permissions))
                                           <div class="col-lg-2 col-sm-2 custom-control custom-checkbox">
                                               {{Form::checkbox('permissions[]','delete '.$module,in_array('delete '.$module,$assignedPermission), ['class'=>'checkbox custom-control-input','id' =>'permission'.$key])}}
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
                               <button type="submit" class="btn btn-primary">Update Role</button>
                               <a href="{{ route('role.index') }}" class="btn btn-warning">Cancel</a>
                           </div>
                       </div>
                   </form>
                </div>
            </div>
        </div>
      </div>
    </div>
</section>
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