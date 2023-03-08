<?php

namespace App\Http\Controllers;

use App\ExploreKeywords;
use Illuminate\Http\Request;
use Modules\Users\Entities\FrontUsers;
use DataTables;
use Validator;
use Redirect;
use DB;

class ExploreKeywordsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.pages.explore-keywords.index');
    }

    public function getAll(){
        $data = ExploreKeywords::latest()->get();
        //echo '<pre>';print_r($data);exit();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="' . route('exploreKeywords.edit', $row->id) . '" data-id="' . $row->id . '" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fa fa-pen"></i></a>';
                $btn .= ' <a href="javascript:void(0)" data-id="' . route('exploreKeywords.destroy', $row->id) . '" class="delete_menu btn btn-danger btn-sm btn-icon" title="Delete"><i class="fa fa-trash"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

   
    public function create(Request $request)
    {
        return view('admin.pages.explore-keywords.create');
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
        $rules['keywords'] = 'required';

        $validator = Validator::make($input, $rules);
         if ($validator->fails()) {
             return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
         } else {
            if (isset($input['ExploreKeywords_id'])) {
                $ExploreKeywords = ExploreKeywords::find($request->ExploreKeywords_id);
                $ExploreKeywords->keywords = $request->keywords;
                $ExploreKeywords->updated_at = Date('Y-m-d H:i:s');
                $msg = "Record Updated successfully.";
            }else {

                $ExploreKeywords = new ExploreKeywords();
                $msg = "Record created successfully.";
                $ExploreKeywords->keywords = $request->keywords;
                $ExploreKeywords->created_at = Date('Y-m-d H:i:s');
            }
             $ExploreKeywords->save();
             return Redirect::route('exploreKeywords.index')->with('success', $msg);
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ExploreKeywords  $exploreKeywords
     * @return \Illuminate\Http\Response
     */
    public function show(ExploreKeywords $exploreKeywords)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ExploreKeywords  $exploreKeywords
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $EditExploreKeywords = ExploreKeywords::find($id);
        return view('admin.pages.explore-keywords.edit', compact('EditExploreKeywords'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ExploreKeywords  $exploreKeywords
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExploreKeywords $exploreKeywords)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ExploreKeywords  $exploreKeywords
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ExploreKeywords = ExploreKeywords::find($id);
        $response = [];
        if (isset($ExploreKeywords) && isset($ExploreKeywords->id)) {
            $ExploreKeywords->delete();
            $response = ['status' => true, "Message" => 'Record deleted successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }
}
