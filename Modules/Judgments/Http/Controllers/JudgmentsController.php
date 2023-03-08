<?php

namespace Modules\Judgments\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Categories\Entities\Categories;
use Modules\Categories\Entities\Judgment;
use Validator,
    Image,
    Redirect;
use DataTables;

class JudgmentsController extends Controller {

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(request $request) {
        if ($request->ajax()) {
            $data = \Modules\Judgments\Entities\Judgment::latest()->get();
            return Datatables::of($data)->addIndexColumn()
                            ->addColumn('select', function($row) {
                                return '<input type="checkbox" name="selectedudgment[]" value="' . $row->id . '" class="selectedudgment" />';
                            })
                            ->addColumn('status', function($row) {
                                if ($row->status == "active") {
                                    $status = isset($row->status) ? '<a data-id="' . route('judgments.status', $row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--primary">Active</span></a>' : '-';
                                } else {
                                    $status = isset($row->status) ? '<a data-id="' . route('judgments.status', $row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--danger">InActive</span></a>' : '-';
                                }

                                return $status;
                            })
                            ->addColumn('date', function($row) {
                                $date = \Carbon\Carbon::parse($row->created_at)->format('d M Y, H:i');
                                return $date;
                            })
                            ->addColumn('action', function($row) {
                                $btn = '<a href="' . route('judgments.edit', $row->id) . '" data-id="' . $row->id . '" class="btn btn-primary btn-sm btn-icon" title="View"><i class="fa fa-pen"></i></a>';
                                $btn .= ' <a href="javascript:void(0)" data-id="' . route('judgments.destroy', $row->id) . '" class="delete_contactus btn btn-danger btn-sm btn-icon" title="Delete"><i class="fa fa-trash"></i></a>';
                                return $btn;
                            })
                            ->rawColumns(['action', 'status', 'select'])
                            ->make(true);
        }
        return view('judgments::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create() {
        $categories = Categories::where('status', 'active')->orderBy('name')->get();
        return view('judgments::create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request) {
        $input = $request->all();
        $rules['name'] = 'required';
        $rules['category_ids'] = 'required';
//        $rules['date'] = 'required';
        $rules['description'] = 'required';
        $rules['image'] = 'mimes:jpeg,png,jpg,gif,svg|max:2048';
        $rules['document'] = 'mimes:pdf,doc|max:2048';
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            if (isset($input['id'])) {
                $services = \Modules\Judgments\Entities\Judgment::find($request->id);
                $msg = "Record Updated successfully.";
            } else {
                $services = new \Modules\Judgments\Entities\Judgment();
                $msg = "Record created successfully.";
            }
            $category_ids = array();
            foreach ($input['category_ids'] as $category_id) {
                $categories = Categories::find($category_id);
                if (!empty($categories)) {
                    $category_ids[] = $category_id;
                } else {
                    $categories_data = Categories::create([
                                'name' => $category_id,
                                'slug' => str_replace(" ", "-", strtolower($category_id)),
                                'status' => 'active',
                    ]);
                    $category_ids[] = $categories_data->id;
                }
            }

            $input['category_ids'] = implode(',', $category_ids);
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
                $request->file('image')->storeAs('public/sitebucket/judgment', $filenametostore);

                //create small thumbnail
                $smallthumbnailpath = public_path('sitebucket/judgment/thumbnail/' . $smallthumbnail);
                $this->createThumbnail($smallthumbnailpath, 150, 93, $request->file('image'));

                //create medium thumbnail
                $mediumthumbnailpath = public_path('sitebucket/judgment/thumbnail/' . $mediumthumbnail);
                $this->createThumbnail($mediumthumbnailpath, 300, 185, $request->file('image'));

                //create large thumbnail
                $largethumbnailpath = public_path('sitebucket/judgment/thumbnail/' . $largethumbnail);
                $this->createThumbnail($largethumbnailpath, 550, 340, $request->file('image'));

                $input['image'] = $filenametostore;
                if ($services->image != '') {
                    removeAllImage('sitebucket/judgment', $services->image);
                }
                $uImage = $request->file('image');
                $destinationPath = public_path('sitebucket/judgment');
                $uImage->move($destinationPath, $input['image']);
            }
            if ($request->hasFile('document')) {
                $filenamewithextension = $request->file('document')->getClientOriginalName();
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                $extension = $request->file('document')->getClientOriginalExtension();
                $filenametostore = $filename . '_' . time() . '.' . $extension;
                $uImage = $request->file('document');
                $destinationPath = public_path('sitebucket/judgment');
                $uImage->move($destinationPath, $filenametostore);
                $input['document'] = $filenametostore;
            }
//            $input['date'] = date('Y-m-d', strtotime($input['date']));
            $services->fill($input)->save();
            return Redirect::route('judgments.index')->with('success', $msg);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id) {
        return view('judgments::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id) {
        $editdata = \Modules\Judgments\Entities\Judgment::find($id);
        $categories = Categories::where('status', 'active')->orderBy('name')->get();

        return view('judgments::edit', compact('editdata', 'categories'));
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
        $Judgments = \Modules\Judgments\Entities\Judgment::find($id);
        $response = [];
        if (isset($Judgments) && isset($Judgments->id)) {
            if ($Judgments->image != '') {
                unlink(public_path('sitebucket/categories/' . $Judgments->image));
            }
            $Judgments->delete();
            $response = ['status' => true, "Message" => 'Record deleted successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }

    public function createThumbnail($path, $width, $height, $getPath) {
        $img = Image::make($getPath->getRealPath())->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save($path);
    }

    public function statusChange($id) {
        $Judgments = \Modules\Judgments\Entities\Judgment::find($id);
        $response = [];
        if (isset($Judgments) && isset($Judgments->status)) {
            if ($Judgments->status == 'active') {
                $Judgments->update([
                    'status' => 'inactive',
                ]);
            } else {
                $Judgments->update([
                    'status' => 'active',
                ]);
            }
            $response = ['status' => true, "Message" => 'Status updated successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }

    public function removedocument($id) {
        $Judgments = \Modules\Judgments\Entities\Judgment::find($id);
        $response = [];
        if (isset($Judgments)) {
            if ($Judgments->document != '') {
                removeAllImage('sitebucket/judgment', $Judgments->document);
            }
            $Judgments->update([
                'document' => '',
            ]);

            $response = ['status' => true, "Message" => 'Remove Document successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }

    public function removeimage($id) {
        $Judgments = \Modules\Judgments\Entities\Judgment::find($id);
        $response = [];
        if (isset($Judgments)) {
            if ($Judgments->image != '') {
                removeAllImage('sitebucket/judgment', $Judgments->image);
            }
            $Judgments->update([
                'image' => '',
            ]);

            $response = ['status' => true, "Message" => 'Remove Image successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }

    function sendletter(Request $request) {

        $Judgmentsdata = $request->selectedudgment;
        foreach ($Judgmentsdata as $Judgmentsid) {
            $Judgments = \Modules\Judgments\Entities\Judgment::find($Judgmentsid);

            $body = array(
                'recipients' => array('list_id' => 'd28e976b8f'),
                'type' => 'regular',
                'settings' => array('subject_line' => $Judgments->name,
                    'reply_to' => 'testineed@gmail.com',
                    'from_name' => 'maulik'
                )
            );
            $api_key = '1414e6c14970563f232595199ad4774e-us13';
            $url = 'https://' . substr($api_key, strpos($api_key, '-') + 1) . '.api.mailchimp.com/3.0/campaigns/';
            $create_campaign = callServer($url, 'POST', $api_key, $body);
            if ($create_campaign) {
                if (!empty($create_campaign->id) && isset($create_campaign->status) && 'save' == $create_campaign->status) {
                    // The campaign id: 

                    $campaign_id = $create_campaign->id;
                    $url = 'https://' . substr($api_key, strpos($api_key, '-') + 1) . '.api.mailchimp.com/3.0/templates';
                    $template_data = array(
                        'name' => $Judgments->name,
                        'html' => $Judgments->description,
                    );
                    $set_template_content = callServer($url, 'POST', $api_key, $template_data);
                    if (isset($set_template_content->id)) {
                        $template_content = array(
                            'template' => array(
                                'id' => $set_template_content->id, // INTEGER
                                'sections' => array(
                                    'std_content00' => $Judgments->description
                                )
                            )
                        );
                        $url = 'https://' . substr($api_key, strpos($api_key, '-') + 1) . '.api.mailchimp.com/3.0/campaigns/' . $campaign_id . '/content';
                        $set_campaign_content = callServer($url, 'PUT', $api_key, $template_content);

                        $url = 'https://' . substr($api_key, strpos($api_key, '-') + 1) . '.api.mailchimp.com/3.0/campaigns/' . $campaign_id . '/actions/send';
                        $send_campaign = callServer("campaigns/$campaign_id/actions/send", 'POST',$api_key);

                        if (empty($send_campaign)) {
                            // Campaign was sent!
                        } elseif (isset($send_campaign->detail)) {
                            $error_detail = $send_campaign->detail;
                        }                         
                    }
                }
            }
        }
        $response = ['status' => true, "Message" => 'Send successfuly', "data" => []];
        return $response;
    }

}
