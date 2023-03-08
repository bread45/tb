<?php

namespace Modules\Faq\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Faq\Entities\Faq;
use Modules\Faq\Entities\FaqCategories;
use DataTables;
use Log;
use Redirect;
use Validator;

class FaqController extends Controller {

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request) {
        if ($request->ajax()) {
            $data = Faq::with('faqcategory');

            return Datatables::of($data)->addIndexColumn()
                            ->addColumn('category', function($row) {
                                return $row->faqcategory->title ? $row->faqcategory->title : '-';
                            })
                            ->addColumn('order_by', function($row) {
                                return '<input type="text" data-bind="' . $row->id . '" style="text-align:center"; class="form-control order_by" value=' . $row->order_by . '>';
                            })
                            ->addColumn('status', function($row) {
                                if ($row->status == "active") {
                                    $status = isset($row->status) ? '<a data-id="' . route('faq.status', $row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--primary">Active</span></a>' : '-';
                                } else {
                                    $status = isset($row->status) ? '<a data-id="' . route('faq.status', $row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--danger">InActive</span></a>' : '-';
                                }
                                return $status;
                            })
                            ->addColumn('action', function($row) {
                                $btn = ' <a href="' . route('faq.edit', $row->id) . '" data-id="' . $row->id . '" class="btn btn-primary btn-sm btn-icon" title="View"><i class="fa fa-pen"></i></a>';
                                $btn .= ' <a href="javascript:void(0)" data-id="' . route('faq.destroy', $row->id) . '" class="delete_contactus btn btn-danger btn-sm btn-icon" title="Delete"><i class="fa fa-trash"></i></a>';
                                return $btn;
                            })
                            ->rawColumns(['action', 'status', 'order_by',])
                            ->make(true);
        }
        return view('faq::faq.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create() {
        $categories = FaqCategories::where('status', 'active')->orderBy('title')->get();
        return view('faq::faq.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request) {
        $input = $request->all();
        $rules['title'] = 'required';
        $rules['status'] = 'required';
        $rules['categories_id'] = 'required';
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            if (isset($input['faq_id'])) {
                $storedata = Faq::find($request->faq_id);
                $msg = "Record Updated successfully.";
            } else {
                $storedata = new Faq();
                $msg = "Record created successfully.";
            }

            if (isset($input['image']) && $input['image'] != '') {
                if ($storedata->image != '') {
                    unlink(public_path('sitebucket/faq/' . $storedata->image));
                }
                $uImage = $request->file('image');
                $input['image'] = $input['title'] . time() . '.' . $uImage->getClientOriginalExtension();
                $destinationPath = public_path('sitebucket/faq');
                $uImage->move($destinationPath, $input['image']);
            }
            $storedata->fill($input)->save();
            return Redirect::route('faq.index')->with('success', $msg);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id) {
        return view('faq::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id) {
        $categories = FaqCategories::where('status', 'active')->orderBy('title')->get();
        $editdata = Faq::find($id);
        return view('faq::faq.edit', compact('categories', 'editdata'));
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
        $destorydata = Faq::find($id);
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
        $statusdata = Faq::find($id);
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
        $statusdata = Faq::find($request->id);
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
