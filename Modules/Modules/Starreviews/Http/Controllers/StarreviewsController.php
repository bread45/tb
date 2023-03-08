<?php

namespace Modules\Starreviews\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Ratings;
use DB,
    DataTables,
    Redirect;
class StarreviewsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Ratings::with(['trainer','user'])->latest('created_at')->get();
            return Datatables::of($data)->addIndexColumn() 
                            ->addColumn('trainer', function ($row) {
                                $name = '';
                                if(isset($row->trainer->business_name) && $row->trainer->business_name != ''){
                                        $name = $row->trainer->business_name;
                                }
                                if(isset($row->trainer->first_name) && $row->trainer->first_name != ''){
                                    $name = $row->trainer->first_name.' '.$row->trainer->last_name;
                                }
                                
                                return $name;
                            })
                            ->addColumn('customer', function ($row) {
                                 $name = '';
                                 if(isset($row->user->first_name)){
                                $name = $row->user->first_name.' '.$row->user->last_name;
                                 }
                                return $name;
                            })
                            ->addColumn('title', function ($row) {
                                $name = $row->title;
                                return $name;
                            })
                            ->addColumn('rating', function ($row) {
                                $rating = '<div class="rating">
                                            <ul class="nav">';
                                if(isset($row->rating)){
                                    for($i=1;$i<=$row->rating;$i++) {
                                        $rating .= '<li><img style="width:15px;vertical-align:top" src="'.asset('/front/images/star.png').'" alt="Rating" /></li>';
                                    }
                                    for($i=5;$i>$row->rating;$i--){
                                        $rating .= '<li><img style="width:15px;vertical-align:top" src="'.asset('/front/images/star-blank.png').'" alt="Rating" /></li>';
                                    }
                                }
                                return $rating;
                            }) 
                            ->addColumn('action', function ($row) {
                                 $btn = ' <a href="javascript:void(0)" data-id="' . route('starreviews.destroy', $row->id) . '" class="delete_contactus btn btn-danger btn-sm btn-icon" title="Delete"><i class="fa fa-trash"></i></a>';
                                return $btn;
                            })
                            ->rawColumns(['action', 'rating'])
                            ->make(true);
        }
        return view('starreviews::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('starreviews::create');
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
        return view('starreviews::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('starreviews::edit');
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
        $Ratings = Ratings::find($id);
        $response = [];
        if (isset($Ratings) && isset($Ratings->id)) { 
            $Ratings->delete();
            $response = ['status' => true, "Message" => 'Ratings & Reviews deleted successfully', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }
}
