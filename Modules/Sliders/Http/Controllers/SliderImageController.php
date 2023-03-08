<?php

namespace Modules\Sliders\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Sliders\Entities\SliderManager;
use Modules\Sliders\Entities\SliderImage;
use DataTables;
use Log;
use Redirect;
use Validator;

class SliderImageController extends Controller {

    /**
     * Display a listing of the resource.
     * @return Response 
     */
    public function index(Request $request, $slider_id = "") {
        if ($request->ajax()) {
            if (isset($request->slider_id)) {
                if ($request->slider_id == 'all') {
                    $data = SliderImage::with('slider')->latest()->get();
                } else {
                    $data = SliderImage::where('slider_id', $request->slider_id)->with('slider')->latest()->get();
                }
            } else {
                $data = SliderImage::with('slider')->latest()->get();
            }

            return Datatables::of($data)->addIndexColumn()
                            ->addColumn('slider', function($row) {
                                return $row->slider->name;
                            })
                            ->addColumn('image', function($row) {
                                $img = isset($row->image) ? '<a data-fancybox="gallery" href="' . url('sitebucket/sliderImage/' . $row->image) . '"><img style="width:50px;" src="' . url('sitebucket/sliderImage/' . $row->image) . '"  alt="slider image"/></a>' : '-';
                                return $img;
                            })
                            ->addColumn('status', function($row) {
                                if ($row->status == "active") {
                                    $status = isset($row->status) ? '<a data-id="' . route('sliderimage.status', $row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--primary">Active</span></a>' : '-';
                                } else {
                                    $status = isset($row->status) ? '<a data-id="' . route('sliderimage.status', $row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--danger">InActive</span></a>' : '-';
                                }

                                return $status;
                            })
                            ->addColumn('action', function($row) {
                                $btn = '<a href="' . route('sliderimage.edit', $row->id) . '" data-id="' . $row->id . '" class="btn btn-primary btn-sm btn-icon" title="View"><i class="fa fa-pen"></i></a>';
                                $btn .= ' <a href="javascript:void(0)" data-id="' . route('sliderimage.destroy', $row->id) . '" class="delete_contactus btn btn-danger btn-sm btn-icon" title="Delete"><i class="fa fa-trash"></i></a>';
                                return $btn;
                            })
                            ->rawColumns(['action', 'status', 'image'])
                            ->make(true);
        }
        $request_slider_id = $slider_id;
        $createdata = SliderManager::where('status', 'active')->get();
        return view('sliders::sliderimage.index', compact('createdata', 'request_slider_id'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create() {
        $createdata = SliderManager::where('status', 'active')->get();
        return view('sliders::sliderimage.create', compact('createdata'));
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
        $rules['slider_id'] = 'required';
        $rules['image'] = 'mimes:jpeg,png,jpg,gif,svg|max:4048';
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            if (isset($input['slider_image_id'])) {
                $store_data = SliderImage::find($request->slider_image_id);
                $msg = "Record Updated successfully.";
            } else {
                $store_data = new SliderImage();
                $msg = "Record created successfully.";
            }
            if (isset($input['image']) && $input['image'] != '') {
                if ($store_data->image != '') {
                    if(file_exists(public_path('sitebucket/sliderImage/' . $store_data->image))){
                        unlink(public_path('sitebucket/sliderImage/' . $store_data->image));
                    }
                }
                $uImage = $request->file('image');
                $input['image'] = time() . '.' . $uImage->getClientOriginalExtension();
                $destinationPath = public_path('sitebucket/sliderImage');
                $uImage->move($destinationPath, $input['image']);
            }
            $store_data->fill($input)->save();
            return Redirect::route('sliderimage.index')->with('success', $msg);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id) {
        return view('sliders::sliderimage.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id) {
        $createdata = SliderManager::where('status', 'active')->get();
        $editdata = SliderImage::find($id);
        return view('sliders::sliderimage.edit', compact('createdata', 'editdata'));
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
        $destorydata = SliderImage::find($id);
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
        $statusdata = SliderImage::find($id);
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
