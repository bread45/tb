<?php

namespace Modules\Permissions\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Permissions\Entities\PermissionManager;
use Modules\Permissions\Entities\Modules;
use DataTables;
use Log;
use Redirect;
use Validator;
use App\Roles;
use App\User;

class PermissionsController extends Controller {

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(request $request) {
        $data = array();
        $data['roles'] = Roles::where('status', 'active')->get();
        $data['modules'] = Modules::with('routes_list')->where('status', 'active')->get();
        $data['permissions'] = PermissionManager::where('role_id', $data['roles'][0]->id)->where('status', 'active')->pluck('route_id')->toArray();
        return view('permissions::index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create() {
        return view('permissions::permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request) {
        $input = $request->all();
        $rules['role_id'] = 'required';
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            $permision = PermissionManager::where('role_id', $request->role_id)->whereIn('route_id', $request->permissionStatus)->pluck('id')->toArray();
            $route_id = PermissionManager::where('role_id', $request->role_id)->whereIn('route_id', $request->permissionStatus)->pluck('route_id')->toArray();
            $mainarray = array_diff($request->permissionStatus, $route_id);
            $notinpermision = PermissionManager::where('role_id', $request->role_id)->WhereNotIn('id', $permision);
            $notinpermision->delete();
            if (!empty($mainarray)) {
                foreach ($mainarray as $key => $value) {
                    PermissionManager::create([
                        'role_id' => $request->role_id,
                        'route_id' => $value
                    ]);
                }
            }
            $msg = "Record Updated successfully.";
            return redirect()->back()->with('success', $msg);
        }
    }

    public function loadPermissions($id) {
        $data = array();
        $data['modules'] = Modules::with('routes_list')->where('status', 'active')->get();
        $data['permissions'] = PermissionManager::where('role_id', $id)->where('status', 'active')->pluck('route_id')->toArray();
        $view = view('permissions::load')->with($data)->render();
        $response = ['status' => true, "Message" => 'Contact successfuly found.', "data" => $view];
        return $response;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id) {
        return view('permissions::permissions.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id) {
        return view('permissions::permissions.edit');
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
        $destorydata = PermissionManager::find($id);
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
        $statusdata = PermissionManager::find($id);
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
