<?php

namespace Modules\Casebooks\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Categories\Entities\Categories;
use Modules\Categories\Entities\Judgment;
use Validator,
    Image,
    Redirect;
use DataTables;

class CasebooksController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(request $request)
    {
        if ($request->ajax()) {
            $data = \Modules\Casebooks\Entities\Casebook::with('Judgment')->with('Users')->latest()->get();
            return Datatables::of($data)->addIndexColumn()
                            ->addColumn('status', function($row) {
                                if ($row->status == "active") {
                                    $status = isset($row->status) ? '<a data-id="' . route('casebooks.status', $row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--primary">Active</span></a>' : '-';
                                } else {
                                    $status = isset($row->status) ? '<a data-id="' . route('casebooks.status', $row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--danger">InActive</span></a>' : '-';
                                }

                                return $status;
                            })
                            ->addColumn('user_id', function($row) {
                                return $row->Users->first_name. ' '.$row->Users->last_name;
                            })
                            ->addColumn('judgement_id', function($row) {
                                return $row->Judgment->name;
                            })
                            ->addColumn('created_at', function($row) {
                                $date = \Carbon\Carbon::parse($row->created_at)->format('D d M Y');
                                return $date;
                            })
                            ->addColumn('action', function($row) {
//                                $btn = '<a href="' . route('judgments.edit', $row->id) . '" data-id="' . $row->id . '" class="btn btn-primary btn-sm btn-icon" title="View"><i class="fa fa-pen"></i></a>';
                                $btn = ' <a href="javascript:void(0)" data-id="' . route('casebooks.destroy', $row->id) . '" class="delete_contactus btn btn-danger btn-sm btn-icon" title="Delete"><i class="fa fa-trash"></i></a>';
                                return $btn;
                            })
                            ->rawColumns(['action', 'status'])
                            ->make(true);
        }
        return view('casebooks::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('casebooks::create');
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
        return view('casebooks::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('casebooks::edit');
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
        $Casebook = \Modules\Casebooks\Entities\Casebook::find($id);
        $response = [];
        if (isset($Casebook) && isset($Casebook->id)) {
            $Casebook->delete();
            $response = ['status' => true, "Message" => 'Record deleted successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }
    
    public function statusChange($id) {
        $Casebook = \Modules\Casebooks\Entities\Casebook::find($id);
        $response = [];
        if (isset($Casebook) && isset($Casebook->status)) {
            if ($Casebook->status == 'active') {
                $Casebook->update([
                    'status' => 'inactive',
                ]);
            } else {
                $Casebook->update([
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
