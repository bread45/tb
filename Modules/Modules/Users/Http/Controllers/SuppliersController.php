<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Users\Entities\FrontUsers;
use Validator;
use DataTables;
use Log;
use Redirect;

class SuppliersController extends Controller {

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(request $request) {
        if ($request->ajax()) {
            $data = FrontUsers::where('user_role', 'supplier')->latest()->get();
            return Datatables::of($data)->addIndexColumn()
                            ->addColumn('status', function($row) {
                                if ($row->status == "active") {
                                    $status = isset($row->status) ? '<a data-id="' . route('suppliers.status', $row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--primary">Active</span></a>' : '-';
                                } else {
                                    $status = isset($row->status) ? '<a data-id="' . route('suppliers.status', $row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--danger">InActive</span></a>' : '-';
                                }

                                return $status;
                            })
                            ->addColumn('action', function($row) {
                                $btn = '<a href="' . route('suppliers.edit', $row->id) . '" data-id="' . $row->id . '" class="btn btn-primary btn-sm btn-icon" title="View"><i class="fa fa-pen"></i></a>';
                                $btn .= ' <a href="javascript:void(0)" data-id="' . route('suppliers.destroy', $row->id) . '" class="delete_contactus btn btn-danger btn-sm btn-icon" title="Delete"><i class="fa fa-trash"></i></a>';
                                return $btn;
                            })
                            ->rawColumns(['action', 'status'])
                            ->make(true);
        }
        return view('users::suppliers.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create() {
        return view('users::suppliers.create');
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
        $rules['phone_number'] = 'numeric';
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
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            if (isset($input['user_id'])) {
                $modules = FrontUsers::find($request->user_id);
                $msg = "Record Updated successfully.";
            } else {
                $modules = new FrontUsers();
                $msg = "Record created successfully.";
            }

            $input['user_role'] = 'supplier';
            $modules->fill($input)->save();
            return Redirect::route('suppliers.index')->with('success', $msg);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id) {
        return view('users::suppliers.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id) {
        $editdata = FrontUsers::find($id);
        return view('users::suppliers.edit', compact('editdata'));
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
