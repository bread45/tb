<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\RequestSteps;
use App\RequestStepsRelation;
use Modules\Services\Entities\Services;
use Modules\Categories\Entities\Categories;

use DataTables;
use Validator;
use Redirect;

use function GuzzleHttp\json_encode;

class RequestStepsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.pages.request-steps.index');
    }

    public function getAll()
    {
        $data = RequestSteps::latest()->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('services', function ($row) {
                if ($row->services) {
                    $row->services = json_decode($row->services);
                    $services = Services::whereIn('id', $row->services)->pluck('name')->toArray();
                    return implode(" , ", $services);
                } else {
                    return '-';
                }
            })
            ->addColumn('status', function ($row) {
                if ($row->status == "active") {
                    $status = isset($row->status) ? '<a data-id="' . route('question.status', $row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--primary">Active</span></a>' : '-';
                } else {
                    $status = isset($row->status) ? '<a data-id="' . route('question.status', $row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--danger">InActive</span></a>' : '-';
                }
                return $status;
            })
            ->addColumn('action', function ($row) {
                $btn = '<a href="' . route('question.edit', $row->id) . '" data-id="' . $row->id . '" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fa fa-pen"></i></a>';
                $btn .= ' <a href="javascript:void(0)" data-id="' . route('question.destroy', $row->id) . '" class="delete_contactus btn btn-danger btn-sm btn-icon" title="Delete"><i class="fa fa-trash"></i></a>';
                return $btn;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Categories::with('services')->whereHas('services')->where('status', 'active')->orderBy('name')->get();
        return view('admin.pages.request-steps.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        if (!isset($input['answer_choice']) || $input['answer_choice'] != 'input') {
            if (isset($input['answers'])) {
                $input['answers'] = array_filter($input['answers']);
                if (!$input['answers'] || count($input['answers']) <= 0) {
                    $input['answers'] = '';
                }
            }
            $rules['answers'] = 'required';
        }

        // $rules['title'] = 'required';
        $rules['question'] = 'required';
        $rules['answer_choice'] = 'required';
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            if ($input['answers'] && count($input['answers']) > 0) {
                $input['answers'] = json_encode($input['answers']);
            }
            if (isset($input['services']) && !empty($input['services']) && count($input['services']) > 0) {
                $input['services'] = json_encode(array_filter($input['services']));
            } else {
                $input['services'] = '';
            }
            if (isset($input['request_step_id'])) {
                RequestStepsRelation::where('request_step_id', $input['request_step_id'])->delete();
                if (isset($input['services']) && !empty($input['services']) && count($request->services) > 0) {
                    foreach (array_filter($request->services) as $key => $value) {
                        if (!empty($value) && $value && $value != null) {
                            RequestStepsRelation::create([
                                'request_step_id' => $input['request_step_id'],
                                'service_id' => $value
                            ]);
                        }
                    }
                }
                $requestSteps = RequestSteps::find($request->request_step_id);
                $msg = "Record Updated successfully.";
            } else {
                $requestSteps = new RequestSteps();
                $msg = "Record created successfully.";
            }
            $requestSteps->fill($input)->save();
            return Redirect::route('question.index')->with('success', $msg);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Categories::with('services')->whereHas('services')->where('status', 'active')->orderBy('name')->get();
        $requestSteps = RequestSteps::find($id);
        $requestSteps->services = json_decode($requestSteps->services);
        $requestSteps->answers = json_decode($requestSteps->answers, true);
        if ($requestSteps->answers && count($requestSteps->answers) > 0) {
            $requestSteps->answers = array_filter($requestSteps->answers);
        }
        return view('admin.pages.request-steps.edit', compact('requestSteps', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $requestSteps = RequestSteps::find($id);
        $response = [];
        if (isset($requestSteps) && isset($requestSteps->id)) {
            $requestSteps->delete();
            $response = ['status' => true, "Message" => 'Record deleted successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }

    /* START : Status change (Active, InActive) */
    public function statusChange($id)
    {
        $requestSteps = RequestSteps::find($id);
        $response = [];
        if (isset($requestSteps) && isset($requestSteps->status)) {
            if ($requestSteps->status == 'active') {
                $requestSteps->update(['status' => 'inactive']);
            } else {
                $requestSteps->update(['status' => 'active']);
            }
            $response = ['status' => true, "Message" => 'Status updated successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }
    /* END : Status change (Active, InActive) */
}
