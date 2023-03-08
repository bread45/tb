<?php

namespace Modules\Advertisementdetails\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Advertisement\Entities\AdvertisementDetails;
use Redirect;
use Validator;
use DataTables,DB;

class AdvertisementdetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
         if ($request->ajax()) {
            $data = AdvertisementDetails::select('advertisements_details.*',DB::raw("price main_price"),DB::raw("sum(price) price"),DB::raw("count(advertisements_details.id) click_count"),  DB::raw('YEAR(created_at) year, MONTHNAME(created_at) month'))->with('Advertisement','user')->groupby('advertisement_id','year','month','user_id')->get();
            return Datatables::of($data)->addIndexColumn() 
                            ->addColumn('name', function ($row) {
                                $name = $row->advertisement->name; 
                                return $name;
                            })  
                            ->addColumn('click_count', function ($row) { 
                                return $row->click_count;
                            })  
                            ->addColumn('main_price', function ($row) { 
                                return $row->main_price;
                            })  
                            ->addColumn('amount', function ($row) { 
                                if($row->advertisement->method == 'fixcost'){
                                    return $row->main_price;
                                }
                                return $row->price;
                            })  
                            ->addColumn('user', function ($row) { 
                                $name = '';
                                if(isset($row->user)){
                                    $name = $row->user->first_name.' '.$row->user->last_name;
                                    if($row->user->business_name != ''){
                                        $name = $row->user->business_name;
                                    }  
                                }
                                return $name;
                            })
                            ->addColumn('month', function ($row) {  
                                return  $row->month;
                            })  
                            ->addColumn('year', function ($row) {  
                                return  $row->year;
                            })  
                            ->rawColumns(['action', 'status'])
                            ->make(true);
        }
        return view('advertisementdetails::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('advertisementdetails::create');
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
        return view('advertisementdetails::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('advertisementdetails::edit');
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
