<?php

namespace Modules\Permissions\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Permissions\Entities\Modules;
use DataTables;
use Log;
use Redirect;
use Validator;

class ModuleController extends Controller {

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(request $request) {
        if ($request->ajax()) {
            $data = Modules::latest()->get();
            return Datatables::of($data)->addIndexColumn()
                            ->addColumn('status', function($row) {
                                if ($row->status == "active") {
                                    $status = isset($row->status) ? '<a data-id="' . $row->id . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--primary">Active</span></a>' : '-';
                                } else {
                                    $status = isset($row->status) ? '<a data-id="' . $row->id . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--danger">InActive</span></a>' : '-';
                                }

                                return $status;
                            })
                            ->addColumn('action', function($row) {
                                $btn = '<a href="' . url('permissions/modules/' . $row->id . '/edit') . '" data-id="' . $row->id . '" class="btn btn-primary btn-sm btn-icon" title="View"><i class="fa fa-pen"></i></a>';
                                $btn .= ' <a href="javascript:void(0)" data-id="' . $row->id . '" class="delete_contactus btn btn-danger btn-sm btn-icon" title="Delete"><i class="fa fa-trash"></i></a>';
                                return $btn;
                            })
                            ->rawColumns(['action', 'status'])
                            ->make(true);
        }
        return view('permissions::modules.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create() {
        return view('permissions::modules.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request) {
        $input = $request->all();
        $rules['name'] = 'required';
        $rules['keyword'] = 'required';
        $rules['status'] = 'required';
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            if (isset($input['modules_id'])) {
                $modules = Modules::find($request->modules_id);
                $msg = "Record Updated successfully.";
            } else {
                $modules = new Modules();
                $msg = "Record created successfully.";
            }
            $modules->fill($input)->save();
            return Redirect::route('modules.index')->with('success', $msg);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id) {
        return view('permissions::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id) {
        $editdata = Modules::find($id);
        return view('permissions::modules.edit', compact('editdata'));
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
        $destorydata = Modules::find($id);
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
        $statusdata = Modules::find($id);
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
