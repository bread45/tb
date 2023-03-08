<?php

namespace Modules\Contactus\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Contactus\Entities\ContactUs;
use DataTables;

class ContactusController extends Controller {

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(request $request) {
        if ($request->ajax()) {
            $data = ContactUs::latest()->get();
            return Datatables::of($data)->addIndexColumn()
                            ->addColumn('action', function($row) {
                                $btn = '<a href="javascript:void(0)" data-id="' . route('contactus.show', $row->id) . '" class="view_contactus btn btn-primary btn-sm btn-icon" title="View"><i class="fa fa-eye"></i></a>';
                                $btn .= ' <a href="javascript:void(0)" data-id="' . $row->id . '" class="delete_contactus btn btn-danger btn-sm btn-icon" title="Delete"><i class="fa fa-trash"></i></a>';
                                return $btn;
                            })
                            ->rawColumns(['action'])
                            ->make(true);
        }
        return view('contactus::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create() {
        return view('contactus::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id) {
        $contactus = ContactUs::find($id);
        if (isset($contactus) && isset($contactus->id)) {
            $view = view('contactus::show', ['contactus' => $contactus])->render();
            $response = ['status' => true, "Message" => 'Contact successfuly found.', "data" => $view];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id) {
        return view('contactus::edit');
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
    public function destroy(Request $Request) {
        $contactus = ContactUs::find($Request->id);
        $response = [];
        if (isset($contactus) && isset($contactus->id)) {
            $contactus->delete();
            $response = ['status' => true, "Message" => 'Contact deleted successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }

}
