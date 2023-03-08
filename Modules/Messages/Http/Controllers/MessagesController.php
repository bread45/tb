<?php

namespace Modules\Messages\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Users\Entities\FrontUsers;
use DataTables,Validator,Redirect;
class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
         if ($request->ajax()) {
            //$data = \Modules\Messages\Entities\Messages::with(['FromUsers','ToUsers'])->where('to_id',0)->orderBy('created_at', 'desc')->get();
            $data = \Modules\Messages\Entities\Messages::with(['FromUsers', 'ToUsers'])->whereRaw('(to_id = 0) or (from_id = 0)')->orderBy('created_at', 'desc')->get()->unique('conversation_id');

            return Datatables::of($data)->addIndexColumn()
                     ->addColumn('date', function($row) {
                                $date = \Carbon\Carbon::parse($row->created_at)->format('D d M Y');
                                return $date;
                            })        
                    ->addColumn('user_id', function($row) {
                                if($row->FromUsers->id == 0){
                                    return $row->ToUsers->first_name. ' '.$row->ToUsers->last_name;
                                }else{
                                    return $row->FromUsers->first_name. ' '.$row->FromUsers->last_name;
                                }
                            })
                            ->addColumn('message', function($row) {
                               // return $row->message;
                                $message = '';
                                if($row->status == "unread" && $row->to_id == 0){
                                $message .= '<lable style="font-weight:bold;color:blue;">'.$row->message.'</label>';  
                                }else{
                                    $message .= $row->message; 
                                }
                                return $message;
                            })
                           
                            ->addColumn('action', function($row) {
//                                $btn = '<a href="' . route('judgments.edit', $row->id) . '" data-id="' . $row->id . '" class="btn btn-primary btn-sm btn-icon" title="View"><i class="fa fa-pen"></i></a>';
                                //$btn = ' <a href="javascript:void(0)" data-id="' . route('messages.destroy', $row->id) . '" class="delete_contactus btn btn-danger btn-sm btn-icon" title="Delete"><i class="fa fa-trash"></i></a>';
                                 $btn = '<a href="' . route('messages.show', $row->id) . '" data-id="' . route('messages.show', $row->id) . '" class="btn btn-primary btn-sm btn-icon" title="View"><i class="fa fa-eye"></i></a>';
                               
                                return $btn;
                            })
                            ->rawColumns(['action', 'message'])
                            ->make(true);
        }
        return view('messages::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $users =  FrontUsers::All();
        return view('messages::create',compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request) {
        $input = $request->all();
       // dd($input);
        $rules['to_id'] = 'required';
        $rules['message'] = 'required';
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            //$conversation = \Modules\Messages\Entities\Messages::with(['FromUsers', 'ToUsers'])->whereRaw('(to_id = ' . $user->id . ' and from_id = ' . $input["to_id"] . ') or (from_id = ' . $user->id . ' and to_id = ' . $input["to_id"] . ')')->first();
            $conversation = \Modules\Messages\Entities\Messages::with(['FromUsers', 'ToUsers'])->whereRaw('(to_id = 0 and from_id = ' . $input["to_id"] . ') or (from_id = 0 and to_id = ' . $input["to_id"] . ')')->first();
            $input['status'] = 'unread';
            if (isset($input['id'])) {
                $services = \Modules\Messages\Entities\Messages::find($request->id);
                $msg = "Message Send successfully.";
            } else {
                $services = new \Modules\Messages\Entities\Messages();
                $msg = "Message Send successfully.";
            }
            $services->fill($input)->save();

            if($conversation != null){
                //get conversation id 
                $conversationId =  $conversation->conversation_id;
                //dd($conversation[0]);
                \Modules\Messages\Entities\Messages::where('id', $services->id)->update([
                 'conversation_id' => $conversationId
                 ]);
             }else{
                 \Modules\Messages\Entities\Messages::where('id', $services->id)->update([
                     'conversation_id' => $services->id
                 ]);
             }
            if(isset($input['type'])){
                return redirect()->back()->with('success', $msg);
            }else{
                return Redirect::route('messages.index')->with('success', $msg);
            }
        }
    }


    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
     public function show($id) {
        $contactus = \Modules\Messages\Entities\Messages::find($id);
        
        $toid = $contactus->from_id;
         if($contactus->from_id == 0){
             $toid = $contactus->to_id;
         }
         $trainer_data = FrontUsers::find($toid);
         $data = \Modules\Messages\Entities\Messages::with(['FromUsers', 'ToUsers'])->whereRaw('(to_id = 0 and from_id = ' . $toid . ') or (from_id = 0 and to_id = ' . $toid . ')')->get();
            $unreadData = \Modules\Messages\Entities\Messages::with(['FromUsers', 'ToUsers'])->where(["to_id" => 0 , "from_id" => $toid, "status" => "unread"])->get();
            $unreadIds =  $unreadData->pluck('id'); 
            \Modules\Messages\Entities\Messages::whereIn('id', $unreadIds)->update([
                'status' => "read"
            ]);
        if (isset($contactus) && isset($contactus->id)) {
             
            return view('messages::show', [
                'contactus' => $contactus,"messagedata" => $data,"trainer_data" => $trainer_data,
                'to_id' => 0,
                'from_id' => $toid
            ]);
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('messages::edit');
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
