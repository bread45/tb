<?php

namespace Modules\Sliders\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Sliders\Entities\SliderManager;
use DataTables;
use Log;
use Redirect;
use Validator;

class SlidersController extends Controller {

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request) {
        if ($request->ajax()) {
            $data = SliderManager::latest()->get();
            return Datatables::of($data)->addIndexColumn()
                            ->addColumn('status', function($row) {
                                if ($row->status == "active") {
                                    $status = isset($row->status) ? '<a data-id="' . route('sliders.status', $row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--primary">Active</span></a>' : '-';
                                } else {
                                    $status = isset($row->status) ? '<a data-id="' . route('sliders.status', $row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--danger">InActive</span></a>' : '-';
                                }
                                return $status;
                            })
                            ->addColumn('action', function($row) {
                                $btn = '<a href="' . route('sliderimage.slider', $row->id) . '" class="btn btn-warning btn-sm btn-icon" title="Images"><i class="fa fa-images"></i></a>';
                                $btn .= ' <a href="' . route('sliders.edit', $row->id) . '" data-id="' . $row->id . '" class="btn btn-primary btn-sm btn-icon" title="View"><i class="fa fa-pen"></i></a>';
                                $btn .= ' <a href="javascript:void(0)" data-id="' . route('sliders.destroy', $row->id) . '" class="delete_contactus btn btn-danger btn-sm btn-icon" title="Delete"><i class="fa fa-trash"></i></a>';
                                return $btn;
                            })
                            ->rawColumns(['action', 'status'])
                            ->make(true);
        }
        return view('sliders::slidermaster.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create() {
        return view('sliders::slidermaster.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request) {
        $input = $request->all();
        $rules['name'] = 'required|unique:sliders,name';
        $rules['status'] = 'required';
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            if (isset($input['slider_id'])) {
                $storedata = SliderManager::find($request->slider_id);
                $msg = "Record Updated successfully.";
            } else {
                $storedata = new SliderManager();
                $msg = "Record created successfully.";
            }
            $storedata->fill($input)->save();
            return Redirect::route('sliders.index')->with('success', $msg);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id) {
        return view('sliders::slidermaster.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id) {
        $editdata = SliderManager::find($id);
        return view('sliders::slidermaster.edit', compact('editdata'));
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
        $destorydata = SliderManager::find($id);
        $response = [];
        if (isset($destorydata) && isset($destorydata->id)) {
            $destorydata->delete();
            $response = ['status' => true, "Message" => 'Record deleted successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }

    public function statusChange($id) {
        $statusdata = SliderManager::find($id);
        $response = [];
        if (isset($statusdata) && isset($statusdata->status)) {
            if ($statusdata->status == 'active') {
                $statusdata->update([
                    'status' => 'inactive'
                ]);
            } else {
                $statusdata->update([
                    'status' => 'active'
                ]);
            }
            $response = ['status' => true, "Message" => 'Status updated successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }

}
