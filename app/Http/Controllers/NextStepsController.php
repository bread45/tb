<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\NextSteps;
use App\NextStepsSections;
use DataTables;
use Validator;
use Redirect;
use DB;



class NextStepsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.pages.next-steps.index');
    }


    public function getAll(){
        $data = NextSteps::latest()->get();
        //echo '<pre>';print_r($data);exit();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="' . route('nextsteps.edit', $row->id) . '" data-id="' . $row->id . '" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fa fa-pen"></i></a>';
                $btn .= ' <a href="javascript:void(0)" data-id="' . route('nextsteps.destroy', $row->id) . '" class="delete_steps btn btn-danger btn-sm btn-icon" title="Delete"><i class="fa fa-trash"></i></a>';
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
    public function create()
    {
        $sections = NextStepsSections::all();
        return view('admin.pages.next-steps.create', compact('sections'));
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
         $rules['slider_section'] = 'required';
         $rules['steps_icon'] = 'mimes:png,jpg,jpeg|max:2048';
         $rules['steps_title'] = 'required|max:35';
         $rules['steps_content'] = 'required|max:150';
         $rules['steps_button_title'] = 'max:18';
         $rules['steps_modal_button_title'] = 'max:18';
         $validator = Validator::make($input, $rules);
         if ($validator->fails()) {
             return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
         } else {
            if (isset($input['next_steps_id'])) {
                $NextSteps = NextSteps::find($request->next_steps_id);
                $keys=NextSteps::pluck('id');
                $NextSteps->section_id = $request->slider_section;
                if(isset($request->steps_icon)){
                $NextSteps->icon = file_get_contents($request->steps_icon);
                }
                $NextSteps->title = $request->steps_title;
                $NextSteps->content = $request->steps_content;
                $NextSteps->button_1 = isset($request->steps_button_title) ? $request->steps_button_title : '';
                $NextSteps->button_1_link = isset($request->steps_button_url) ? $request->steps_button_url : '';
                $NextSteps->button_2 = isset($request->steps_modal_button_title) ? $request->steps_modal_button_title : '';
                $NextSteps->modal_title = isset($request->steps_modal_title) ? $request->steps_modal_title : '';
                $NextSteps->modal_content = isset($request->steps_modal_content) ? $request->steps_modal_content : '';
                $NextSteps->created_at = Date('Y-m-d H:i:s');
                $NextSteps->updated_at = Date('Y-m-d H:i:s');
                $msg = "Record Updated successfully.";
            }else {

             $NextSteps = new NextSteps();
             $msg = "Record created successfully.";
             $NextSteps->section_id = $request->slider_section;
             if(isset($request->steps_icon)){
             $NextSteps->icon = file_get_contents($request->steps_icon);
             }
             $NextSteps->title = $request->steps_title;
             $NextSteps->content = $request->steps_content;
             $NextSteps->button_1 = isset($request->steps_button_title) ? $request->steps_button_title : '';
             $NextSteps->button_1_link = isset($request->steps_button_url) ? $request->steps_button_url : '';
             $NextSteps->button_2 = isset($request->steps_modal_button_title) ? $request->steps_modal_button_title : '';
             $NextSteps->modal_title = isset($request->steps_modal_title) ? $request->steps_modal_title : '';
             $NextSteps->modal_content = isset($request->steps_modal_content) ? $request->steps_modal_content : '';
             $NextSteps->created_at = Date('Y-m-d H:i:s');
             $NextSteps->updated_at = Date('Y-m-d H:i:s');
            }
             $NextSteps->save();
             return Redirect::route('nextsteps.index')->with('success', $msg);
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\NextSteps  $nextSteps
     * @return \Illuminate\Http\Response
     */
    public function show(NextSteps $nextSteps)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\NextSteps  $nextSteps
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sections = NextStepsSections::all();
        $NextSteps = NextSteps::find($id);
        // dd($NextSteps);
        return view('admin.pages.next-steps.edit', compact('NextSteps', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\NextSteps  $nextSteps
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NextSteps $nextSteps)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\NextSteps  $nextSteps
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $NextSteps = NextSteps::find($id);
        $response = [];
        if (isset($NextSteps) && isset($NextSteps->id)) {
            $NextSteps->delete();
            $response = ['status' => true, "Message" => 'Record deleted successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }
}
