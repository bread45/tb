<?php

namespace Modules\Advertisement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Advertisement\Entities\Advertisement;
use Modules\Locations\Entities\Locations;
use Redirect;
use Validator;
use DataTables;
use Image;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Advertisement::where('is_deleted',0)->latest()->get();
            return Datatables::of($data)->addIndexColumn() 
                            ->addColumn('name', function ($row) {
                                $name = $row->name; 
                                return $name;
                            }) 
                            ->addColumn('start_date', function ($row) {
                                $date = date('d-m-Y',strtotime($row->start_date)); 
                                return $date;
                            }) 
                            ->addColumn('end_date', function ($row) {
                                $date = date('d-m-Y',strtotime($row->end_date)); 
                                return $date;
                            }) 
                            ->addColumn('amount', function ($row) { 
                                return $row->amount;
                            }) 
                            ->addColumn('click_count', function ($row) { 
                                return $row->click_count;
                            }) 
                            ->addColumn('status', function ($row) {
                                if ($row->status == "active") {
                                    $status = isset($row->status) ? '<a data-id="' . route('advertisement.status', $row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--primary">Active</span></a>' : '-';
                                } else {
                                    $status = isset($row->status) ? '<a data-id="' . route('advertisement.status', $row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--danger">InActive</span></a>' : '-';
                                }

                                return $status;
                            })
                            ->addColumn('action', function ($row) {
                                $btn = '<a href="' . route('advertisement.edit', $row->id) . '" data-id="' . $row->id . '" class="btn btn-primary btn-sm btn-icon" title="View"><i class="fa fa-pen"></i></a>';
                 
                                $btn .= ' <a href="javascript:void(0)" data-id="' . route('advertisement.destroy', $row->id) . '" class="delete_contactus btn btn-danger btn-sm btn-icon" title="Delete"><i class="fa fa-trash"></i></a>';
                                return $btn;
                            })
                            ->rawColumns(['action', 'status'])
                            ->make(true);
        }
        return view('advertisement::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $Locations = Locations::where('status','active')->get();
        return view('advertisement::create',compact('Locations'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        
        $rules['name'] = 'required'; 
        $rules['start_date'] = 'required|date_format:d-m-Y|before:end_date'; 
        $rules['end_date'] = 'required|date_format:d-m-Y'; 
        $rules['status'] = 'required'; 
        $rules['locations'] = 'required'; 
        $rules['amount'] = 'required|numeric'; 
        if (!isset($input['id'])) {
            $rules['image'] = 'required';  
        } 
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            if (isset($input['id'])) {
                $advertisement = Advertisement::find($request->id);
                $msg = "Record Updated successfully.";
            } else {
                $advertisement = new Advertisement();
                $msg = "Record created successfully.";
            }
            $input['start_date'] = date('Y-m-d',strtotime($input['start_date']));
            $input['end_date'] = date('Y-m-d',strtotime($input['end_date']));
            $input['locations'] =  implode(',', $input['locations']);
            if($input['pageview'] == 'explore'){
                $input['view'] = 'horizontal';
            }
             if ($request->hasFile('image')) {
                //get filename with extension
                $filenamewithextension = $request->file('image')->getClientOriginalName();

                //get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

                //get file extension
                $extension = $request->file('image')->getClientOriginalExtension();

                //filename to store
                $filenametostore = 'advertisement_' . time() . '.' . $extension;

                //small thumbnail name
//                $smallthumbnail = 'small_' . $filename . '_' . time() . '.' . $extension;
//
//                //medium thumbnail name
//                $mediumthumbnail = 'medium_' . $filename . '_' . time() . '.' . $extension;
//
//                //large thumbnail name
//                $largethumbnail = 'large_' . $filename . '_' . time() . '.' . $extension;
//                //Upload File
//                
//                //create small thumbnail
//                $smallthumbnailpath = public_path('sitebucket/advertisement/thumbnail/' . $smallthumbnail);
//                $this->createThumbnail($smallthumbnailpath, 150, 93, $request->file('image'));
//
//                //create medium thumbnail
//                $mediumthumbnailpath = public_path('sitebucket/advertisement/thumbnail/' . $mediumthumbnail);
//                $this->createThumbnail($mediumthumbnailpath, 300, 185, $request->file('image'));
//
//                //create large thumbnail
//                $largethumbnailpath = public_path('sitebucket/advertisement/thumbnail/' . $largethumbnail);
//                $this->createThumbnail($largethumbnailpath, 550, 340, $request->file('image'));
                $uImage = $request->file('image');
                if($input['view'] == 'horizontal'){
                    $img = Image::make($uImage->getRealPath());
//                     $img->resize(970, 90);
                     $img->resize(1170, 220);
//                     $img->resize(1170, 220, function ($constraint) {
//                        $constraint->aspectRatio(); });
                }else{
                    $img = Image::make($uImage->getRealPath());
                        $img->resize(160, 600);
                }
                
                $input['image'] = $filenametostore;
                if ($advertisement->image != '') {
                    removeAllImage('sitebucket/advertisement', $advertisement->image);
                }
                
                $destinationPath = public_path('sitebucket/advertisement');
                $img->save($destinationPath . '/' . $input['image']);
//                $uImage->move($destinationPath, $input['image']);

            }
            $advertisement->fill($input)->save();   
            return Redirect::route('advertisement.index')->with('success', $msg);
        }
    }
    
    public function createThumbnail($path, $width, $height, $getPath)
    {
        $img = Image::make($getPath->getRealPath())->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save($path);
    }
    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('advertisement::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $advertisement = Advertisement::find($id);
        $Locations = Locations::where('status','active')->get();
        return view('advertisement::edit',compact('advertisement','Locations'));
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
        $categories = Advertisement::find($id);
        $response = [];
        if (isset($categories) && isset($categories->id)) { 
            $categories->is_deleted = 1;
            $categories->status = 'inactive';
            $categories->save();
            $response = ['status' => true, "Message" => 'Record deleted successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }

    public function statusChange($id)
    {
        $categories = Advertisement::find($id);
        $response = [];
        if (isset($categories) && isset($categories->status)) {
            if ($categories->status == 'active') {
                $categories->update([
                    'status' => 'inactive',
                ]);
            } else {
                $categories->update([
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
