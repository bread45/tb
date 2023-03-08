<?php

namespace Modules\CMSPages\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\CMSPages\Entities\CMSPages;
use Log;
use Redirect;
use Validator;
use DataTables;
use Image;

class CMSPagesController extends Controller {

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request) {
        if ($request->ajax()) {
            $data = CMSPages::latest();
            return Datatables::of($data)->addIndexColumn()
                             
                            ->addColumn('status', function ($row) {
                                if ($row->status == "active") {
                                    $status = isset($row->status) ? '<a data-id="' . route('cms_pages.status', $row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--primary">Active</span></a>' : '-';
                                } else {
                                    $status = isset($row->status) ? '<a data-id="' . route('cms_pages.status', $row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--danger">InActive</span></a>' : '-';
                                }
                                return $status;
                            })
                            ->addColumn('action', function ($row) {
                                $btn = ' <a href="' . route('cms_pages.edit', base64_encode($row->id)) . '" data-id="' . $row->id . '" class="btn btn-primary btn-sm btn-icon" title="View"><i class="fa fa-pen"></i></a>';
                                $btn .= ' <a href="javascript:void(0)" data-id="' . route('cms_pages.destroy', $row->id) . '" class="delete_contactus btn btn-danger btn-sm btn-icon" title="Delete"><i class="fa fa-trash"></i></a>';
                                return $btn;
                            })
                            ->rawColumns(['action', 'status', 'order_by', 'tags'])
                            ->make(true);
        }
        return view('cmspages::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create() {
        return view('cmspages::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request) {
        $input = $request->all();
        $rules['title'] = 'required';
        $rules['status'] = 'required';
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            if (isset($input['cmspages_id'])) {
                $storedata = CMSPages::find($request->cmspages_id);
                $msg = "Record Updated successfully.";
            } else {
                $storedata = new CMSPages();
                $msg = "Record created successfully.";
            }
            $input['slug'] = $this->slugify($input['title']);
            // if (isset($input['banner_image']) && $input['banner_image'] != '') {
            //     if ($storedata->banner_image != '') {
            //         unlink(public_path('sitebucket/cmspages/' . $storedata->banner_image));
            //     }
            //     $uImage = $request->file('banner_image');
            //     $input['banner_image'] = time() . '.' . $uImage->getClientOriginalExtension();
            //     $destinationPath = public_path('sitebucket/cmspages');
            //     $uImage->move($destinationPath, $input['banner_image']);
            // }
            if ($request->hasFile('banner_image')) {
                //get filename with extension
                $filenamewithextension = $request->file('banner_image')->getClientOriginalName();

                //get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

                //get file extension
                $extension = $request->file('banner_image')->getClientOriginalExtension();

                //filename to store
                $filenametostore = $filename . '_' . time() . '.' . $extension;

                //small thumbnail name
                $smallthumbnail = 'small_' . $filename . '_' . time() . '.' . $extension;

                //medium thumbnail name
                $mediumthumbnail = 'medium_' . $filename . '_' . time() . '.' . $extension;

                //large thumbnail name
                $largethumbnail = 'large_' . $filename . '_' . time() . '.' . $extension;
                //Upload File
                $request->file('banner_image')->storeAs('public/images/cmspages', $filenametostore);
                $request->file('banner_image')->storeAs('public/images/cmspages/thumbnail', $smallthumbnail);
                $request->file('banner_image')->storeAs('public/images/cmspages/thumbnail', $mediumthumbnail);
                $request->file('banner_image')->storeAs('public/images/cmspages/thumbnail', $largethumbnail);

                //create small thumbnail
                $smallthumbnailpath = public_path('images/cmspages/thumbnail/' . $smallthumbnail);
                $this->createThumbnail($smallthumbnailpath, 150, 93, $request->file('banner_image'));

                //create medium thumbnail
                $mediumthumbnailpath = public_path('images/cmspages/thumbnail/' . $mediumthumbnail);
                $this->createThumbnail($mediumthumbnailpath, 300, 185, $request->file('banner_image'));

                //create large thumbnail
                $largethumbnailpath = public_path('images/cmspages/thumbnail/' . $largethumbnail);
                $this->createThumbnail($largethumbnailpath, 550, 340, $request->file('banner_image'));

                $input['banner_image'] = $filenametostore;
                if ($storedata->banner_image != '') {
                    removeAllImage('images/cmspages', $storedata->banner_image);
                }
                $uImage = $request->file('banner_image');
                $destinationPath = public_path('images/cmspages');
                $uImage->move($destinationPath, $input['banner_image']);

            }
            $storedata->fill($input)->save();

            return Redirect::route('cms_pages.index')->with('success', $msg);
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
        return view('cmspages::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id) {
        $id =  base64_decode($id);
        $editdata = CMSPages::find($id);
        return view('cmspages::edit', compact('editdata'));
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

    public function slugify($text) {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id) {
        $destorydata = CMSPages::find($id);
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
        $statusdata = CMSPages::find($id);
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

    public function orderChange(Request $request) {
        $statusdata = CMSPages::find($request->id);
        $response = [];
        if (isset($statusdata) && isset($statusdata->status)) {

            $statusdata->update([
                'order_by' => $request->order
            ]);

            $response = ['status' => true, "Message" => 'Order updated successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }

}
