<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\SubscriptionPlan;

use Stripe;
use DataTables;
use Validator;
use Redirect;
use DB;

class SubcriptionPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.pages.subcription-plan.index');
    }

    public function getAll()
    {
        $data = SubscriptionPlan::latest()->get();
        //echo '<pre>';print_r($data);exit();
        return Datatables::of($data)->addIndexColumn()
           /* ->addColumn('status', function ($row) {
                if ($row->status == "1") {
                    $status = isset($row->status) ? '<a data-id="' . route('subcriptionplan.status', $row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--primary">Active</span></a>' : '-';
                } else {
                    $status = isset($row->status) ? '<a data-id="' . route('subcriptionplan.status', $row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--danger">InActive</span></a>' : '-';
                }
                return $status;
            })*/
            ->addColumn('action', function ($row) {
                $btn = '<a href="' . route('subcriptionplan.edit', $row->id) . '" data-id="' . $row->id . '" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fa fa-pen"></i></a>';
                //$btn .= ' <a href="javascript:void(0)" data-id="' . route('subcriptionplan.destroy', $row->id) . '" class="delete_contactus btn btn-danger btn-sm btn-icon" title="Delete"><i class="fa fa-trash"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function gettransactionall()
    {
        $paymentDatas = DB::select("select * from provider_orders as po join front_users as fu on po.trainer_id=fu.id order by po.id desc");
        //$data = SubscriptionPlan::latest()->get();
        // echo '<pre>';print_r($data);exit();
        foreach($paymentDatas as $paymentdata){
            Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY')); 
            if($paymentdata->subscription_status !== "cancelled"){
               $subscription = \Stripe\Subscription::retrieve(
                   $paymentdata->stripe_subscription_id
                );

                $paymentdata->subscription_status = ucfirst($subscription->status);
            }
            else {
                $paymentdata->subscription_status = "Cancelled";
            }
            $arrayData = array(
                'first_name' => $paymentdata->first_name,
                'last_name' => $paymentdata->last_name,
                'email' => $paymentdata->email,
                'plan_type' => $paymentdata->plan_type,
                'amount' => $paymentdata->amount,
                'start_date' => $paymentdata->start_date,
                'subscription_status' => $paymentdata->subscription_status,
                'cancel_date' => $paymentdata->cancel_date,
            );
            $data[] = $arrayData;
        }
        return Datatables::of($data)->addIndexColumn()
           
            //->rawColumns(['action'])
            ->make(true);
    }

    public function provider_transaction_history(){
        return view('admin.pages.subcription-plan.transaction_history');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.subcription-plan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        
       // $rules['subcription_plan'] = 'required';
        $rules['price'] = 'required';
        //$rules['days'] = 'required';
       // $rules['description'] = 'required';
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            if (isset($input['subcription_plan_id'])) {
                $subcriptionPlan = SubscriptionPlan::find($request->subcription_plan_id);
                try {
                    $product = \Stripe\Product::create([
                         'name' => 'provider plans',
                         'type' => 'service',
                     ]);
                    $productId = $product->id;
                    $input['product_id'] = $productId;
                    $subcriptionPlan->product_id = $productId;
                    if($input['subcription_plan'] == 'yearly'){
                        $plan_id =  \Stripe\Plan::create([
                            'amount' => $input['price']*100,
                            'currency' => 'usd',
                            'interval' => 'year',
                            'product' => $productId,
                        ]);
                    } else {
                        $plan_id =  \Stripe\Plan::create([
                             'amount' => $input['price']*100,
                             'currency' => 'usd',
                             'interval' => 'month',
                             'product' => $productId,
                         ]);
                    }
                    $input['plan_id'] = $plan_id->id;
                    $subcriptionPlan->plan_id = $plan_id->id;
                    $subcriptionPlan->free_trial_months = $request->free_trial_months;
                } catch(\Stripe\Exception\CardException $e) {
                 //Since it's a decline, \Stripe\Exception\CardException will be caught
            
                echo 'Message is:' . $e->getError()->message . '\n';
               //dd($e->getError()->message);
               Session::flash('error', $e->getError()->message);
               return redirect()->back()->withInput($request->all());
               } catch (\Stripe\Exception\RateLimitException $e) {
                 //Too many requests made to the API too quickly
                //dd($e->getError()->message);
                 Session::flash('error', 'Too many requests made to the API too quickly');
                return redirect()->back()->withInput($request->all());
               } catch (\Stripe\Exception\InvalidRequestException $e) {
                 //Invalid parameters were supplied to Stripe's API
                //dd($e->getError()->message);
                 Session::flash('error', 'Invalid parameters were supplied to Stripe API');
                 return redirect()->back()->withInput($request->all());
               } catch (\Stripe\Exception\AuthenticationException $e) {
                 //Authentication with Stripe's API failed
                 //(maybe you changed API keys recently)
                //dd($e->getError()->message);
                 Session::flash('error', 'Authentication with Stripe API failed');
                 return redirect()->back()->withInput($request->all());
               } catch (\Stripe\Exception\ApiConnectionException $e) {
                // Network communication with Stripe failed
                //dd($e->getError()->message);
                 Session::flash('error', 'Network communication with Stripe failed');
                 return redirect()->back()->withInput($request->all());
               } catch (\Stripe\Exception\ApiErrorException $e) {
                 //Display a very generic error to the user, and maybe send
                 //yourself an email
                //dd($e->getError()->message);
              //   Session::flash('error', 'Display a very generic error to the user, and maybe send');
                 return redirect()->back()->withInput($request->all());
               } catch (Exception $e) {
                 //Something else happened, completely unrelated to Stripe
              //dd($e->getError()->message);
                 Session::flash('error', 'Something else happened, completely unrelated to Stripe');
                 return redirect()->back()->withInput($request->all());
               }
               // Updated Trail Period for all the records
               $keys=SubscriptionPlan::pluck('id');
               $updateTrail = SubscriptionPlan::whereIn('id', $keys)->update(['free_trial_months' => $request->free_trial_months]);
			   $msg = "Record Updated successfully.";
            } else {
                $subcriptionPlan = new SubscriptionPlan();
                $msg = "Record created successfully.";
            }
            // echo '<pre>';print_r($input);exit();
            $subcriptionPlan->fill($input)->save();
            return Redirect::route('subcriptionplan.index')->with('success', $msg);
        }

        if (isset($input['free_trial_months'])) {
            echo '<pre>';print_r($input);exit();
            $updateTrail = SubscriptionPlan::where('id', 'id')->update(['free_trial_months' => $request->free_trial_months]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subscriptionPlan = SubscriptionPlan::find($id);
        // echo '<pre>';print_r($subscriptionPlan);exit();
        return view('admin.pages.subcription-plan.edit', compact('subscriptionPlan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subscriptionPlan = SubscriptionPlan::find($id);
        $response = [];
        if (isset($subscriptionPlan) && isset($subscriptionPlan->id)) {
            $subscriptionPlan->delete();
            $response = ['status' => true, "Message" => 'Record deleted successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }
    /* START : Status change (Active, InActive) */
    public function statusChange($id)
    {
        $subscriptionPlan = SubscriptionPlan::find($id);
        $response = [];
        if (isset($subscriptionPlan) && isset($subscriptionPlan->is_active)) {
            if ($subscriptionPlan->is_active == '1') {
                $subscriptionPlan->update(['is_active' => '0']);
            } else {
                $subscriptionPlan->update(['is_active' => '1']);
            }
            $response = ['status' => true, "Message" => 'Status updated successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }
    /* END : Status change (Active, InActive) */
}
