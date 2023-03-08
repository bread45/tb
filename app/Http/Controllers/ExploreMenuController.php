<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\ExploreMenu;
use Modules\Users\Entities\FrontUsers;
use DataTables;
use Validator;
use Redirect;
use DB;


class ExploreMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.pages.explore-menu-items.index');
    }



    
    public function getAll(){
        $data = ExploreMenu::latest()->get();
        //echo '<pre>';print_r($data);exit();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="' . route('exploreItems.edit', $row->id) . '" data-id="' . $row->id . '" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fa fa-pen"></i></a>';
                $btn .= ' <a href="javascript:void(0)" data-id="' . route('exploreItems.destroy', $row->id) . '" class="delete_menu btn btn-danger btn-sm btn-icon" title="Delete"><i class="fa fa-trash"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menuItemsCity = FrontUsers::select('city')->where('user_role', '=', 'trainer')->distinct()->orderBy('city', 'ASC')->get();
        $menuItemsState = FrontUsers::select('state_code')->where('user_role', '=', 'trainer')->distinct()->orderBy('state_code', 'ASC')->get();
        return view('admin.pages.explore-menu-items.create', compact('menuItemsCity', 'menuItemsState'));
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
        $rules['city'] = 'required';
        $rules['state'] = 'required';

        $validator = Validator::make($input, $rules);
         if ($validator->fails()) {
             return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
         } else {
            if (isset($input['ExploreMenu_id'])) {
                $ExploreMenu = ExploreMenu::find($request->ExploreMenu_id);
                $ExploreMenu->city = $request->city;
                $ExploreMenu->state = $request->state;
                $ExploreMenu->updated_at = Date('Y-m-d H:i:s');
                $msg = "Record Updated successfully.";
            }else {

             $ExploreMenu = new ExploreMenu();
             $msg = "Record created successfully.";
             $ExploreMenu->city = $request->city;
             $ExploreMenu->state = $request->state;
             $ExploreMenu->created_at = Date('Y-m-d H:i:s');
            }
             $ExploreMenu->save();
             return Redirect::route('exploreItems.index')->with('success', $msg);
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ExploreMenu  $exploreMenu
     * @return \Illuminate\Http\Response
     */
    public function show(ExploreMenu $exploreMenu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ExploreMenu  $exploreMenu
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menuItemsCity = FrontUsers::select('city')->where('user_role', '=', 'trainer')->distinct()->orderBy('city', 'ASC')->get();
        $menuItemsState = FrontUsers::select('state_code')->where('user_role', '=', 'trainer')->distinct()->orderBy('state_code', 'ASC')->get();
        $EditExploreMenu = ExploreMenu::find($id);
        return view('admin.pages.explore-menu-items.edit', compact('menuItemsCity', 'menuItemsState', 'EditExploreMenu'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ExploreMenu  $exploreMenu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExploreMenu $exploreMenu)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ExploreMenu  $exploreMenu
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ExploreMenu = ExploreMenu::find($id);
        $response = [];
        if (isset($ExploreMenu) && isset($ExploreMenu->id)) {
            $ExploreMenu->delete();
            $response = ['status' => true, "Message" => 'Record deleted successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }
}
