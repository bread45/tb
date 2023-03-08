
        <div class="friend-list-main">
             @foreach($orders as $key => $order)
            <?php
            $date = explode("-", $order->service_date);

            $startDate = strtotime($order->start_date);
            $startDate = date('j F Y', $startDate);

            $endDate = strtotime($order->start_date);
            $endDate = date('j F Y', $endDate);
            ?>
            <div class="bg-white card mb-4 order-list shadow-md">
                <div class="gold-members p-4">
                    <span class="mb-3 d-block"> {{$startDate}} - {{$endDate}} {{$order->service_time}} </span>
                    <input type="hidden" id="order_comment_data_{{$order->id}}" value="{{$order->order_note}}" />
                    <div class="media">
                        <a href="#">
                         <img class="mr-4" src="@if($order->trainer->photo != ''){{asset('front/profile/'.$order->trainer->photo)}}@else {{ asset('/front/images/details_default.png')}} @endif" alt="{{$order->trainer->first_name}} {{$order->trainer->last_name}}" />
                     </a>
                     <div class="media-body"> 
                        <h6 class="mb-2"> 
                            <a href="#" class="text-black"><h5>{{$order->service->name}}</h5></a>
                            <a href="#" class="text-black">{{$order->trainer->first_name}} {{$order->trainer->last_name}}</a>
                        </h6> 
                        <hr>
                        <div class="float-right">
                            <button class="btn btn-outline-danger profile-btn add-note" onclick="openmodel({{$order->id}})">Add Note</button>
                        </div>
                        <p class="mb-0 pt-2"><span class="text-black font-weight-bold"> Total Paid:</span> ${{$order->amount}}
                        </p>
                    </div>
                </div>

            </div>
        </div>
        @endforeach
    </div>
</div>
</div>