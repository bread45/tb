<?php

namespace Modules\Services\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Categories\Entities\Categories;
use Modules\Services\Entities\Services;
use Modules\Users\Entities\FrontUsers;
use Validator;
use DataTables;
use Log;
use Redirect;
use Image;
use App\RequestSteps;
use App\RequestStepsRelation;

class ServicesController extends Controller {

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index() {
        return view('services::index');
    }

    /* START : Datatable response for list view */

    public function getAll() {
        $data = Services::with('category')->latest()->get();
        return Datatables::of($data)->addIndexColumn()
                        ->addColumn('category', function($row) {
                            return (isset($row->category) && isset($row->category->name)) ? $row->category->name : '-';
                        })
                        ->addColumn('status', function($row) {
                            if ($row->status == "active") {
                                $status = isset($row->status) ? '<a data-id="' . route('services.status',$row->id). '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--primary">Active</span></a>' : '-';
                            } else {
                                $status = isset($row->status) ? '<a data-id="' . route('services.status',$row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--danger">InActive</span></a>' : '-';
                            }
                            return $status;
                        })
                        ->addColumn('image', function($row) {
                            $img = isset($row->image) ? '<a data-fancybox="gallery" href="' . url('sitebucket/services/' . $row->image) . '"><img style="width:50px;" src="' . url('sitebucket/services/' . $row->image) . '"  alt = "image"/></a>' : '-';
                            return $img;
                        })
                        ->addColumn('action', function($row) {
                            $btn = '<a href="' . route('services.edit',$row->id) . '" data-id="' . $row->id . '" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fa fa-pen"></i></a>';
                            $btn .= ' <a href="javascript:void(0)" data-id="' . route('services.destroy',$row->id) . '" class="delete_contactus btn btn-danger btn-sm btn-icon" title="Delete"><i class="fa fa-trash"></i></a>';
                            return $btn;
                        })
                        ->rawColumns(['action', 'image', 'status'])
                        ->make(true);
    }

    /* END : Datatable response for list view */

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create() {
        $categories = Categories::where('status', 'active')->orderBy('name')->get();
        $spot_supplier=FrontUsers::where('status','active')->where('user_role','supplier')->get();
        $requeststeps=RequestSteps::where('status','active')->get();
        return view('services::create', compact('categories','spot_supplier','requeststeps'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request) {
        $input = $request->all();
      
        $rules['name'] = 'required';
        $rules['status'] = 'required';
        $rules['category_id'] = 'required';
        $rules['image'] = 'mimes:jpeg,png,jpg,gif,svg|max:2048';
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            if (isset($input['service_id'])) {
                $services = Services::find($request->service_id);
                $msg = "Record Updated successfully.";
            } else {
                $services = new Services();
                $msg = "Record created successfully.";
            }
            // if (isset($input['image']) && $input['image'] != '') {
            //     if ($services->image != '') {
            //         unlink(public_path('sitebucket/services/' . $services->image));
            //     }
            //     $uImage = $request->file('image');
            //     $input['image'] = time() . '.' . $uImage->getClientOriginalExtension();
            //     $destinationPath = public_path('sitebucket/services');
            //     $uImage->move($destinationPath, $input['image']);
            // }
            if ($request->hasFile('image')) {
                //get filename with extension
                $filenamewithextension = $request->file('image')->getClientOriginalName();

                //get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

                //get file extension
                $extension = $request->file('image')->getClientOriginalExtension();

                //filename to store
                $filenametostore = $filename . '_' . time() . '.' . $extension;

                //small thumbnail name
                $smallthumbnail = 'small_' . $filename . '_' . time() . '.' . $extension;

                //medium thumbnail name
                $mediumthumbnail = 'medium_' . $filename . '_' . time() . '.' . $extension;

                //large thumbnail name
                $largethumbnail = 'large_' . $filename . '_' . time() . '.' . $extension;
                //Upload File
                $request->file('image')->storeAs('public/sitebucket/services', $filenametostore);
                $request->file('image')->storeAs('public/sitebucket/services/thumbnail', $smallthumbnail);
                $request->file('image')->storeAs('public/sitebucket/services/thumbnail', $mediumthumbnail);
                $request->file('image')->storeAs('public/sitebucket/services/thumbnail', $largethumbnail);

                //create small thumbnail
                $smallthumbnailpath = public_path('sitebucket/services/thumbnail/' . $smallthumbnail);
                $this->createThumbnail($smallthumbnailpath, 150, 93, $request->file('image'));

                //create medium thumbnail
                $mediumthumbnailpath = public_path('sitebucket/services/thumbnail/' . $mediumthumbnail);
                $this->createThumbnail($mediumthumbnailpath, 300, 185, $request->file('image'));

                //create large thumbnail
                $largethumbnailpath = public_path('sitebucket/services/thumbnail/' . $largethumbnail);
                $this->createThumbnail($largethumbnailpath, 550, 340, $request->file('image'));

                $input['image'] = $filenametostore;
                if ($services->image != '') {
                    removeAllImage('sitebucket/services', $services->image);
                }
                $uImage = $request->file('image');
                $destinationPath = public_path('sitebucket/services');
                $uImage->move($destinationPath, $input['image']);

            }
            $input['slug'] =  str_replace(" ", "-", strtolower($input['name']));
            $services->fill($input)->save();
            // if (isset($input['service_id'])) {
            //     RequestStepsRelation::where('service_id',$services->id)->delete();
            // }
            // if(!empty($input['dynamic_step'])){
            //     foreach($input['dynamic_step'] as $a=>$b){
            //         RequestStepsRelation::create([
            //             'request_step_id'=>$b,
            //             'service_id'=>$services->id
            //         ]);
            //     }
            // }
            return Redirect::route('services.index')->with('success', $msg);
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
    public function show($id) {
        return view('services::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id) {
        $edittags="";
        $service = Services::find($id);
        $categories = Categories::where('status', 'active')->orderBy('name')->get();
        $spot_supplier=FrontUsers::where('status','active')->where('user_role','supplier')->get();
        // $requeststeps=RequestSteps::whereRaw('services', '%like%', $id)->where('status','active')->get();
        $requeststeps=RequestSteps::where('services', 'like', '%"'.$id.'"%')->where('status','active')->get();
        $stepselected=RequestStepsRelation::where('service_id',$id)->pluck('request_step_id')->toArray();
        if(!empty($service->tags)){
            $edittags=implode(',',array_column(json_decode($service->tags),'value'));
        }
        return view('services::edit', compact('categories','service','edittags','spot_supplier','requeststeps','stepselected'));
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
        $services = Services::find($id);
        $response = [];
        if (isset($services) && isset($services->id)) {
            if ($services->image != '') {
                unlink(public_path('sitebucket/services/' . $services->image));
            }
            $services->delete();
            $response = ['status' => true, "Message" => 'Record deleted successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }

    /* START : Status change (Active, InActive) */

    public function statusChange($id) {
        $services = Services::find($id);
        $response = [];
        if (isset($services) && isset($services->status)) {
            if ($services->status == 'active') {
                $services->update(['status' => 'inactive']);
            } else {
                $services->update(['status' => 'active']);
            }
            $response = ['status' => true, "Message" => 'Status updated successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }

    /* END : Status change (Active, InActive) */
}
