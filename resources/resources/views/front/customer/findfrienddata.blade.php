@if(isset($customerlist) && $customerlist->count() > 0)
@foreach($customerlist as $customers)
<div class="nearby-user">
    <div class="row">
        <div class="col-md-2 col-sm-2">
            <img  src="@if(isset($customers->photo) && !empty($customers->photo) && file_exists(public_path('front/profile/'.$customers->photo))) {{ asset('front/profile/'.$customers->photo)}} @else {{ asset('/front/images/details_default.png')}}  @endif" alt="user" class="profile-photo-lg d-flex mr-3 rounded-circle img-thumbnail thumb-lg">
        </div>
        <div class="col-md-7 col-sm-7">
           
            <h5><a href="{{route('customer.newprofile',$customers->first_name.'-'.$customers->last_name.'-'.$customers->id)}}" class="profile-link">{{$customers->first_name}} {{$customers->last_name}}</a></h5>
            
            <p>{{$customers->city}} {{$customers->state}}</p> 
        </div>
        <div class="col-md-3 col-sm-3">
 
            @php $frienddata = \App\Friend::whereRaw('user_id = '.$customers->id.' and friend_id = '.Auth::guard('front_auth')->user()->id)->orwhereRaw('user_id = '.Auth::guard('front_auth')->user()->id.' and friend_id = '.$customers->id)->first(); @endphp
            
            @if(empty($frienddata))
                <button class="btn btn-primary add-friend" data-id='{{$customers->id}}'>Add Friend</button>
            @elseif($frienddata->accept == 0)
                <button class="btn btn-danger">Requested</button>
            @else
                    <button class="btn btn-success" style="cursor:none;">Friend</button>
            @endif
        </div>
    </div>
</div>
@endforeach
 {!! $customerlist->links('pagination') !!} 
 
@else 
 <div class="col-lg-12 col-md-12 col-12 mb-4">
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            Sorry, We didn't find any results matching this search.

        </div>
    </div>
 @endif 