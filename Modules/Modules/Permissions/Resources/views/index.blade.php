@extends('admin.layouts.default')
@section('title', 'Permission Master')
@section('content')

<link href="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Permission Master</h3>
            <!--<span class="kt-subheader__separator kt-subheader__separator--v"></span>-->
        </div>
        <!--        <div class="kt-subheader__toolbar">
                    <a href="{{route('routes.create')}}" class="btn btn-primary btn-bold"><i class="fa fa-plus"></i> Add New</a>
                </div>-->
    </div>
</div>
<!-- end:: Content Head -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <form method="POST" enctype="multipart/form-data" action="{{route('manager.store')}}" class="kt-form kt-form--label-right" _lpchecked="1">
        @csrf
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Set Permission On Role
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                @include('admin.theme.includes.message')
                <!--begin: Datatable -->
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">Roles:</label>
                    <div class="col-sm-4">
                        <select name="role_id" class="form-control input-sm valid role_id" aria-invalid="false">
                            @if(!$roles->isEmpty())
                            @foreach($roles as $k=>$v)
                            <option value="{{$v->id}}">{{$v->name}}</option>
                            @endforeach
                            @endif
                        </select>
                        <span class="form-text text-muted">Please Select Role</span>
                    </div>
                    <hr/>
                </div>

                <!--end: Datatable -->
            </div>

        </div>
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Enable/Disable Route Access
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                <!--begin: Datatable -->
                <div class="permission_control ajax_replace">
                    @if(!$modules->isEmpty())
                    @foreach($modules as $a=>$b)
                    <div class="form-group row">
                        <div class="col-md-12 col-lg-12">
                            <h6>{{$b->name}}</h6>
                            <hr>
                        </div>
                        @if(!$b->routes_list->isEmpty())
                        @foreach($b->routes_list as $c=>$d)
                        <div class="col-md-4">
                            <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                                <input @if(in_array($d->id, $permissions)) checked @endif type="checkbox" name="permissionStatus[]" class="route_checkbox" value="{{$d->id}}"> {{$d->label}}
                                        <span></span>
                            </label>
                        </div>
                        @endforeach
                        @endif
                    </div>
                    @endforeach
                    @endif
                </div>
                <!--end: Datatable -->
            </div>
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <div class="row">
                        <div class="col-lg-6">
                            <button type="submit" name="submit" class="btn btn-primary">Save</button>
                            <button type="reset" class="btn btn-secondary reset">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>
<!-- end:: Content -->
@stop

@section('pagescript')
<!--begin::Page Vendors(used by this page) -->
<script src="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<!--end::Page Vendors -->
<script>
$(document).on('change', '.role_id', function () {
    var id = $(this).val();
    $.ajax({
        url: "{{url('permissions/manager/loadpermission/')}}/" + id,
        type: 'GET',
        success: function (result) {
            if (result.status == true) {
                $('.ajax_replace').html(result.data);
            } else {
                toastr.error('Something went wrong please try again.');
            }
        }
    });
});
</script>
@endsection