<?php

namespace Modules\Orders\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Validator,
    Image,
    Redirect;
use DataTables;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        
        if ($request->ajax()) {
            $data = \Modules\Orders\Entities\Orders::with(['Users','trainer','Ratting'])->latest()->get();
            return Datatables::of($data)->addIndexColumn()
                            
                            ->addColumn('date', function($row) {
                              
                                $startDate = date('j F Y', strtotime($row->start_date));            
                                $endDate = date('j F Y',strtotime($row->end_date) );
                                $date = '';
                                if(($startDate == $endDate) || $row->stripe_subscription_id){
                                    $date .= $startDate;;
                                }else{
                                    $date .= $startDate .'-<br/>'.$endDate;
                                }
                                $date .= '<br/>'. $row->service_time ;
                                                
                                //$date = \Carbon\Carbon::parse($row->created_at)->format('d M Y, H:i');
                                return $date;
                            })
                            ->addColumn('user_id', function($row) {
                                return $row->Users->first_name. ' '.$row->Users->last_name;
                            })
                            ->addColumn('amount', function($row) {
                                $amount = '';
                                if($row->stripe_subscription_id){
                                    if($row->plan_type == "monthly"){
                                       $amount .= $row->service->price_monthly. ' USD Monthly';
                                    }if($row->plan_type == "weekly"){
                                       $amount .= $row->service->price_weekly. ' USD Weekly';
                                    }    
                                }else{
                                    $amount .= '$'.$row->amount.' USD';
                                }
                           
                                return $amount;
                            })
                            ->addColumn('admin_fees', function($row) {
                                return $row->admin_fees. '%';
                            })
                            ->addColumn('trainer_payment', function($row) {
                                if($row->stripe_subscription_id){
                                    if($row->plan_type == "monthly"){
                                       $amount = $row->service->price_monthly;
                                    }if($row->plan_type == "weekly"){
                                       $amount = $row->service->price_weekly;
                                    }    
                                }else{
                                    $amount = $row->amount;
                                }
                                $adminFees =  ($row->admin_fees/100)*$amount;
                                $trainerPayment = $amount - $adminFees;
                                return '$'.$trainerPayment.' USD';
                            })
                            ->addColumn('payment_date', function($row) {   
                                if($row->stripe_subscription_id){
                                    $date = date('j F Y',strtotime($row->start_date));
                                }else{
                                    $date = date('j F Y',strtotime($row->created_at));
                                }   
                                return $date;
                            })
                            ->addColumn('order_status', function($row) {
                                $status = '';
                                if(!empty($row->stripe_refund_id) ){
                                    $status .= '<span class="badge badge-danger">Cancelled</span>';
                                    if(!empty($row->refund_amount)){
                                       $status .= '<br/><span style="margin-top: 8px;" class="badge badge-light">Refunded: $'.$row->refund_amount.' USD</span>'; 
                                    }
                                }else{
                                    $status .= '<span class="badge badge-success">Confirmed</span><br/>';
                                }
                                return $status;
                            })
                            ->addColumn('review', function($row) {
                                $review = '<div class="d-flex align-items-center justify-content-start">
                                                            <div class="rating">
                                                                <ul class="nav">';
                                if(isset($row->Ratting->rating)){
                                    for($i=1;$i<=$row->Ratting->rating;$i++) {
                                        $review .= '<li><img src="'.asset('/front/images/star.png').'" alt="Rating" /></li>';
                                    }
                                    for($i=5;$i>$row->Ratting->rating;$i--) {
                                         $review .= '<li><img src="'.asset('/front/images/star.png').'" alt="Rating" /></li>';
                                    }
                                }
                                $review .= '</ul>
                                                            </div>
                                                        </div>';
                                return $review;
                            })
                            ->addColumn('action', function($row) {
                                $btn = '<a href="' . route('orders.view', $row->id) . '" data-id="' . $row->id . '" class="btn btn-primary btn-sm btn-icon" title="View"><i class="fa fa-eye"></i></a>';
                                //$btn .= ' <a href="' . route('judgments.edit', $row->id) . '" data-id="' . $row->id . '" class="btn btn-primary btn-sm btn-icon" title="View"><i class="fa fa-pen"></i></a>';
                                //$btn .= ' <a href="javascript:void(0)" data-id="' . route('judgments.destroy', $row->id) . '" class="delete_contactus btn btn-danger btn-sm btn-icon" title="Delete"><i class="fa fa-trash"></i></a>';
                                return $btn;
                            })
                            ->rawColumns(['action','review','date','order_status'])
                            ->make(true);
        }
        return view('orders::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('orders::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $order_details = \Modules\Orders\Entities\Orders::with(['Users','trainer','Ratting','service'])->find($id);
        return view('orders::show',compact('order_details'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('orders::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
