<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Users\Entities\FrontUsers;
use Modules\Locations\Entities\Locations;
use App\ProviderLocations;
use Validator;
use DataTables;
use Log;
use DB;
use Redirect,Mail;
use App\Countries;
use App\TrainerServices;

class OrganisersController extends Controller {

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(request $request) {
        if ($request->ajax()) {
            $data = FrontUsers::where('id','!=',0)->latest()->get();
            return Datatables::of($data)->addIndexColumn()
                            ->addColumn('status', function($row) {
                                if ($row->status == "active") {
                                    $status = isset($row->status) ? '<a data-id="' . route('users.status',$row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--primary">Active</span></a>' : '-';
                                } else {
                                    $status = isset($row->status) ? '<a data-id="' . route('users.status',$row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--danger">InActive</span></a>' : '-';
                                }

                                return $status;
                            })
                            ->addColumn('user_role', function($row) {
                                 return ucfirst($row->user_role);
                            })
                            ->addColumn('action', function($row) {
                                $btn = '<a href="' . route('users.edit',  base64_encode($row->id)) . '" data-id="' . $row->id . '" class="btn btn-primary btn-sm btn-icon" title="View"><i class="fa fa-pen"></i></a>';
                                $btn .= ' <a href="javascript:void(0)" data-id="' . route('users.destroy',$row->id) . '" class="delete_contactus btn btn-danger btn-sm btn-icon" title="Delete"><i class="fa fa-trash"></i></a>';
                                return $btn;
                            })
                            ->rawColumns(['action', 'status'])
                            ->make(true);
        }
        return view('users::organisers.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create() {
        $Countries = Countries::All();
        $locations = Locations::where('status','active')->get();
        return view('users::organisers.create',compact('Countries','locations'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request) {
        $input = $request->all();
        
        $rules['first_name'] = 'required';
        $rules['last_name'] = 'required';
        //$rules['phone_number'] = 'numeric';
        if (isset($input['user_id'])) {
            if (isset($input['password']) && !empty($input['password'])) {
                $rules['password'] = 'min:6';
                $rules['confirm_password'] = 'required_with:password|same:password|min:6';
                $input['password'] = \Hash::make($input['password']);
            } else {
                unset($input['password']);
            }
            $rules['email'] = 'required|email|unique:front_users,email,' . $input['user_id'];
        } else {
            $rules['password'] = 'min:6';
            $rules['confirm_password'] = 'required_with:password|same:password|min:6';
            $rules['email'] = 'required|email|unique:front_users,email';
            $password = $input['password'];
            $input['password'] = \Hash::make($input['password']);
        } 
        if (isset($input['user_id'])) {
            $bussiness_name = DB::table('front_users')->select('business_name')->where('id', $input['user_id'])->first();
            
            $string = $bussiness_name->business_name;
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
            $input['spot_description'] = $slug;
        } else {
            $string = $input['first_name'].' '.$input['last_name'];
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
            $input['spot_description'] = $slug;
        }
        if(!empty($input['providerlocations'])){
            $input['service_location'] =  implode(',', $input['providerlocations']);
        }else{
            $input['service_location'] =  '';
        }
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            if (isset($input['user_id'])) {
                $modules = FrontUsers::find($request->user_id);
                $msg = "Record Updated successfully.";
		$modules->fill($input)->save();
            } else {
                $modules = new FrontUsers();
                $input['confirmed'] = 1;
                $msg = "Record created successfully.";
                $modules->fill($input)->save();
                 $subject = "Welcome - Training Block";
                $emails_name = 'Training Block';
                $admin_email = "auto-reply@trainingblockusa.com";
                $admin_name = "Training Block";  
                $emails = $input['email'];
                try {
                    if($input['user_role'] == "customer"){
                     
                        /*Mail::send('email.register_user', ["user" => $modules], function ($message) use ($admin_email, $admin_name, $subject, $emails, $emails_name) {
                            $message->from($admin_email, $admin_name);
                            $message->to($emails, $emails_name)->subject($subject);
                        });*/

                    }else if($input['user_role'] == "trainer"){
                        /*Mail::send('email.register', [
                            'last_name' => ucfirst($input['last_name']),
                            'email' => $input['email'],
                            'password' => $password,
                            'first_name' => $input['first_name'],
                            'user_role' => "Provider",
                        ], function ($message) use ($admin_email, $admin_name, $subject, $emails, $emails_name) {
                            $message->from($admin_email, $admin_name);
                            $message->to($emails, $emails_name)->subject($subject);
                        }); */
                    }
                   
                } catch (Exception $exc) {
                    echo $exc->getTraceAsString();
                }
               
            }

            
            return Redirect::route('users.index')->with('success', $msg);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id) {
        return view('users::organisers.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id) {
        $id = base64_decode($id);
        $editdata = FrontUsers::find($id);
        $Countries = Countries::All();
        $locations = Locations::where('status','active')->get();
        $providerlocations = ProviderLocations::where('user_id',$id)->pluck('location_id')->toArray();

        if(empty($editdata) || $editdata->id == 0){
            return Redirect::route('users.index');
    }
        return view('users::organisers.edit', compact('editdata','Countries','locations','providerlocations'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id) {
        $destorydata = FrontUsers::find($id);
        $response = [];
        if (isset($destorydata) && isset($destorydata->id)) {
            $destorydata->delete();
             TrainerServices::where('trainer_id',$destorydata->id)->delete();
             DB::table('coupons')->where('trainer_id', $destorydata->id)->delete();
             DB::table('friends')->where('user_id', $destorydata->id)->delete();
             DB::table('friends')->where('friend_id', $destorydata->id)->delete();
             DB::table('invite_friend')->where('user_id', $destorydata->id)->delete();
             DB::table('messages')->where('from_id', $destorydata->id)->delete();
             DB::table('messages')->where('to_id', $destorydata->id)->delete();
             DB::table('notifications')->where('from_user_id', $destorydata->id)->delete();
             DB::table('notifications')->where('to_user_id', $destorydata->id)->delete();
             //DB::table('orders')->where('user_id', $destorydata->id)->delete();
             DB::table('provider_locations')->where('user_id', $destorydata->id)->delete();
             DB::table('provider_scheduling')->where('trainer_id', $destorydata->id)->delete();
             DB::table('provider_scheduling_service')->where('trainer_id', $destorydata->id)->delete();
             DB::table('provider_service_book')->where('trainer_id', $destorydata->id)->delete();
             DB::table('ratings')->where('trainer_id', $destorydata->id)->delete();
             DB::table('ratings')->where('user_id', $destorydata->id)->delete();
             DB::table('recommended_providers')->where('user_id', $destorydata->id)->delete();
             DB::table('recommended_providers')->where('provider_id', $destorydata->id)->delete();
             DB::table('resource')->where('trainer_id', $destorydata->id)->delete();
             DB::table('resource_comments')->where('user_id', $destorydata->id)->delete();
             DB::table('resource_count')->where('user_id', $destorydata->id)->delete();
             //DB::table('stripe_accounts')->where('user_id', $destorydata->id)->delete();
             DB::table('trainer_photo')->where('trainer_id', $destorydata->id)->delete();
             DB::table('trainer_resource_photo')->where('trainer_id', $destorydata->id)->delete();
            $response = ['status' => true, "Message" => 'Record deleted successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }

    public function statusChange($id) {
        $statusdata = FrontUsers::find($id);
        $response = [];
        if (isset($statusdata) && isset($statusdata->status)) {
            if ($statusdata->status == 'active') {
                $statusdata->update([
                    'status' => 'inactive'
                ]);
            } else {
                
                $statusdata->status = "active";
                $statusdata->confirmed = 1;
                $statusdata->confirmation_code = null;
                $statusdata->save();
                // $statusdata->update([
                //     'status' => 'active',
                //     'confirmed' => "1",
                //     'confirmation_code' => ""
                // ]);
                // dd($statusdata);
            }
            $response = ['status' => true, "Message" => 'Status updated successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }

}
