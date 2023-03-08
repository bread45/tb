@if(isset($grouplist) && $grouplist->count() > 0)
@foreach($grouplist as $group)
<div class="nearby-user">
    <div class="row">
        <div class="col-md-2 col-sm-2">
            <img src="@if(isset($group->image) && !empty($group->image) && file_exists(public_path('front/profile/'.$group->image))) {{ asset('front/profile/'.$group->image)}} @else {{ asset('front/images/details_default.png')}}  @endif" alt="user" class="profile-photo-lg">
        </div>
        <div class="col-md-7 col-sm-7">
            <h5><a href="#" class="profile-link">{{$group->name}}</a></h5>
             
        </div>
        <div class="col-md-3 col-sm-3">
        @php $groupuserdata = \App\GroupsUsers::where('group_id',$group->id)->where('user_id',$user->id)->first();
        @endphp 
        @if(!empty($groupuserdata))
            <button class="btn btn-success pull-right" data-id="{{$group->id}}">Joined</button>
        @else
            <button class="btn btn-primary pull-right join-group" data-id="{{$group->id}}">Join</button>
        @endif
            
        </div>
    </div>
</div>
@endforeach
 {!! $grouplist->links('pagination') !!} 
 
@else 
 <div class="col-lg-12 col-md-12 col-12 mb-4">
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            Sorry, We didn't find any results matching this search.

        </div>
    </div>
 @endif 