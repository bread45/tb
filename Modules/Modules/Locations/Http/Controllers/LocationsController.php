<?php

namespace Modules\Locations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Locations\Entities\Locations;
use Redirect;
use Validator;
use DataTables;
class LocationsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
         if ($request->ajax()) {
            $data = Locations::latest()->get();
            return Datatables::of($data)->addIndexColumn() 
                            ->addColumn('name', function ($row) {
                                $name = $row->name; 
                                return $name;
                            }) 
                            ->addColumn('status', function ($row) {
                                if ($row->status == "active") {
                                    $status = isset($row->status) ? '<a data-id="' . route('locations.status', $row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--primary">Active</span></a>' : '-';
                                } else {
                                    $status = isset($row->status) ? '<a data-id="' . route('locations.status', $row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--danger">InActive</span></a>' : '-';
                                }

                                return $status;
                            })
                            ->addColumn('action', function ($row) {
                                $btn = '<a href="' . route('locations.edit', $row->id) . '" data-id="' . $row->id . '" class="btn btn-primary btn-sm btn-icon" title="View"><i class="fa fa-pen"></i></a>';
                 
                                $btn .= ' <a href="javascript:void(0)" data-id="' . route('locations.destroy', $row->id) . '" class="delete_contactus btn btn-danger btn-sm btn-icon" title="Delete"><i class="fa fa-trash"></i></a>';
                                return $btn;
                            })
                            ->rawColumns(['action', 'status'])
                            ->make(true);
        }
        return view('locations::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('locations::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
     {
        $input = $request->all();
        if (isset($input['id'])) {
            $rules['name'] = 'required|unique:locations,name,' . $input['id'];
        } else {
            $rules['name'] = 'required|unique:locations,name';
        } 
        $rules['latitude'] = 'required'; 
        $rules['longitude'] = 'required'; 
        $rules['status'] = 'required'; 
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            if (isset($input['id'])) {
                $categories = Locations::find($request->id);
                $msg = "Record Updated successfully.";
            } else {
                $categories = new Locations();
                $msg = "Record created successfully.";
            }  
            $categories->fill($input)->save();
            return Redirect::route('locations.index')->with('success', $msg);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('locations::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $editdata = Locations::find($id);
        return view('locations::edit',  compact('editdata'));
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
        $categories = Locations::find($id);
        $response = [];
        if (isset($categories) && isset($categories->id)) { 
            $categories->delete();
            $response = ['status' => true, "Message" => 'Record deleted successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }

    public function statusChange($id)
    {
        $categories = Locations::find($id);
        $response = [];
        if (isset($categories) && isset($categories->status)) {
            if ($categories->status == 'active') {
                $categories->update([
                    'status' => 'inactive',
                ]);
            } else {
                $categories->update([
                    'status' => 'active',
                ]);
            }
            $response = ['status' => true, "Message" => 'Status updated successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }
}
