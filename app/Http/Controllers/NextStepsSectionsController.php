<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\NextStepsSections;
use DataTables;
use Validator;
use Redirect;
use DB;


class NextStepsSectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.pages.next-steps-sections.index');
    }

    public function getAll(){
        $data = NextStepsSections::latest()->get();
        // echo '<pre>';print_r($data);exit();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="' . route('nextsections.edit', $row->id) . '" data-id="' . $row->id . '" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fa fa-pen"></i></a>';
                // $btn .= ' <a href="javascript:void(0)" data-id="' . route('nextstepssections.destroy', $row->id) . '" class="delete_steps btn btn-danger btn-sm btn-icon" title="Delete"><i class="fa fa-trash"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

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
        $rules['section_title'] = 'required';
        $rules['slider_title'] = 'required';
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
           if (isset($input['next_section_id'])) {
            $NextStepsSections = NextStepsSections::find($request->next_section_id);
            $keys=NextStepsSections::pluck('id');
            $NextStepsSections->section_title = isset($request->section_title) ? $request->section_title : '';
            $NextStepsSections->slider_title = isset($request->slider_title) ? $request->slider_title : '';
            $NextStepsSections->created_at = Date('Y-m-d H:i:s');
            $NextStepsSections->updated_at = Date('Y-m-d H:i:s');
            $msg = "Record Updated successfully.";
        }
         $NextStepsSections->save();
         return Redirect::route('nextsections.index')->with('success', $msg);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\NextStepsSections  $nextStepsSections
     * @return \Illuminate\Http\Response
     */
    public function show(NextStepsSections $nextStepsSections)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\NextStepsSections  $nextStepsSections
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $NextStepsSections = NextStepsSections::find($id);
        // dd($NextStepsSections);
        return view('admin.pages.next-steps-sections.edit', compact('NextStepsSections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\NextStepsSections  $nextStepsSections
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NextStepsSections $nextStepsSections)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\NextStepsSections  $nextStepsSections
     * @return \Illuminate\Http\Response
     */
    public function destroy(NextStepsSections $nextStepsSections)
    {
        //
    }
}
