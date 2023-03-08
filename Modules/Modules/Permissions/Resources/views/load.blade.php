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
