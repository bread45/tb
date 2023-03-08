<?php

namespace Modules\Testimonials\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Testimonials\Entities\Testimonials;
use Modules\Users\Entities\FrontUsers;
use DataTables;
use Log;
use Redirect;
use Validator;

class TestimonialsController extends Controller {

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request) {
        if ($request->ajax()) {
            $data = Testimonials::latest();
            return Datatables::of($data)->addIndexColumn()
                            ->addColumn('status', function($row) {
                                if ($row->status == "active") {
                                    $status = isset($row->status) ? '<a data-id="' . route('testimonials.status', $row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--primary">Active</span></a>' : '-';
                                } else {
                                    $status = isset($row->status) ? '<a data-id="' . route('testimonials.status', $row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--danger">InActive</span></a>' : '-';
                                }
                                return $status;
                            })                            
                            ->addColumn('action', function($row) {
                                $btn = ' <a href="' . route('testimonials.edit', $row->id) . '" data-id="' . $row->id . '" class="btn btn-primary btn-sm btn-icon" title="View"><i class="fa fa-pen"></i></a>';
                                $btn .= ' <a href="javascript:void(0)" data-id="' . route('testimonials.destroy', $row->id) . '" class="delete_contactus btn btn-danger btn-sm btn-icon" title="Delete"><i class="fa fa-trash"></i></a>';
                                return $btn;
                            })
                            ->rawColumns(['action', 'status','show_slider', 'order_by'])
                            ->make(true);
        }
        return view('testimonials::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create() {
        return view('testimonials::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request) {
        $input = $request->all();
        $rules['title'] = 'required';
        $rules['sub_title'] = 'required';
        $rules['status'] = 'required';
        $rules['order_by'] = 'numeric';
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            if (isset($input['testimonial_id'])) {
                $storedata = Testimonials::find($request->testimonial_id);
                $msg = "Record Updated successfully.";
            } else {
                $storedata = new Testimonials();
                $msg = "Record created successfully.";
            }
            $storedata->fill($input)->save();
            return Redirect::route('testimonials.index')->with('success', $msg);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id) {
        return view('testimonials::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id) {
        $editdata = Testimonials::find($id);
        return view('testimonials::edit', compact('editdata'));
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
        $destorydata = Testimonials::find($id);
        $response = [];
        if (isset($destorydata) && isset($destorydata->id)) {
            $destorydata->delete();
            $response = ['status' => true, "Message" => 'Record deleted successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }
    public function showSliderChange($id) {
        $statusdata = Testimonials::find($id);
        $response = [];
        if (isset($statusdata) && isset($statusdata->show_slider)) {
            if ($statusdata->show_slider == 'active') {
                $statusdata->update([
                    'show_slider' => 'inactive'
                ]);
            } else {
                $statusdata->update([
                    'show_slider' => 'active'
                ]);
            }
            $response = ['status' => true, "Message" => 'Status updated successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }
    public function statusChange($id) {
        $statusdata = Testimonials::find($id);
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

    public function orderChange(Request $request) {
        $statusdata = Testimonials::find($request->id);
        $response = [];
        if (isset($statusdata) && isset($statusdata->status)) {

            $statusdata->update([
                'order_by' => $request->order
            ]);

            $response = ['status' => true, "Message" => 'Order updated successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }

}
