<?php

namespace Modules\Blogs\Http\Controllers;

use DataTables;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Image;
use Modules\Blogs\Entities\BlogCategories;
use Modules\Blogs\Entities\Blogs;
//use Modules\Blogs\Entities\BlogTagMaster;
//use Modules\Blogs\Entities\BlogTags;
use Redirect;
use Validator;

class BlogsController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Blogs::with('category')->orderBy('id','desc')->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('category', function ($row) {
                    return $row->category->title;
                })
                /*->addColumn('order_by', function ($row) {
                    return '<input type="text" data-bind="' . $row->id . '" style="text-align:center"; class="form-control order_by" value=' . $row->order_by . '>';
                })*/
                /*->addColumn('tags', function ($row) {
                    $rtn = "";
                    if (!$row->tags->isEmpty()) {
                        foreach ($row->tags as $t => $a) {
                            if (!empty($a->tags_list)) {
                                $rtn .= '<span style="margin-top:3px;" class="kt-badge kt-badge--inline kt-badge--info">' . $a->tags_list->name . '</span> &nbsp';
                            }
                        }
                    }
                    return $rtn;
                })*/
                ->addColumn('status', function ($row) {
                    if ($row->status == "active") {
                        $status = isset($row->status) ? '<a data-id="' . route('blogs.status', $row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--primary">Active</span></a>' : '-';
                    } else {
                        $status = isset($row->status) ? '<a data-id="' . route('blogs.status', $row->id) . '" href="javascript:"  class="status_change"><span class="kt-badge kt-badge--inline kt-badge--danger">InActive</span></a>' : '-';
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $btn = ' <a href="' . route('blogs.edit', $row->id) . '" data-id="' . $row->id . '" class="btn btn-primary btn-sm btn-icon" title="View"><i class="fa fa-pen"></i></a>';
                    $btn .= ' <a href="javascript:void(0)" data-id="' . route('blogs.destroy', $row->id) . '" class="delete_contactus btn btn-danger btn-sm btn-icon" title="Delete"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action', 'status', 'order_by', 'tags'])
                ->make(true);
        }
        return view('blogs::blogsmaster.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $categories = BlogCategories::where('status', 'active')->orderBy('title')->get();
        //$tags = BlogTagMaster::where('status', 'active')->orderBy('name')->get();
        return view('blogs::blogsmaster.create', compact('categories'/*, 'tags'*/));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $rules['title'] = 'required';
        $rules['status'] = 'required';
        $rules['blog_category_id'] = 'required';
        $msg['blog_category_id.required'] = "The blog category field is required.";
        $validator = Validator::make($request->all(), $rules,$msg);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
        } else {
            if (isset($input['blog_id'])) {
                $storedata = Blogs::find($request->blog_id);
                $msg = "Record Updated successfully.";
            } else {
                $storedata = new Blogs();
                $msg = "Record created successfully.";
            }

            $input['created_time'] = \Carbon\Carbon::parse($input['created_time'])->format('Y-m-d H:s:i');
            $input['slug'] = $this->slugify($input['title']);
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
                
                //create small thumbnail
                $smallthumbnailpath = public_path('sitebucket/blog/thumbnail/' . $smallthumbnail);
                $this->createThumbnail($smallthumbnailpath, 150, 93, $request->file('image'));

                //create medium thumbnail
                $mediumthumbnailpath = public_path('sitebucket/blog/thumbnail/' . $mediumthumbnail);
                $this->createThumbnail($mediumthumbnailpath, 300, 185, $request->file('image'));

                //create large thumbnail
                $largethumbnailpath = public_path('sitebucket/blog/thumbnail/' . $largethumbnail);
                $this->createThumbnail($largethumbnailpath, 550, 340, $request->file('image'));

                $input['image'] = $filenametostore;
                if ($storedata->image != '') {
                    removeAllImage('sitebucket/blog', $storedata->image);
                }
                $uImage = $request->file('image');
                $destinationPath = public_path('sitebucket/blog');
                $uImage->move($destinationPath, $input['image']);

            }
            $storedata->fill($input)->save();
            /*if (!empty($input['tag_id'])) {

                if (isset($input['blog_id'])) {
                    BlogTags::where('blog_id', $storedata->id)->delete();
                }

                foreach ($input['tag_id'] as $t => $tid) {
                    BlogTags::create([
                        'blog_id' => $storedata->id,
                        'tag_id' => $tid,
                    ]);
                }

            }*/
            return Redirect::route('blogs.index')->with('success', $msg);
        }
    }
    public function createThumbnail($path, $width, $height, $getPath)
    {
        //$path = str_replace("\\", "/", $path);
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
        return view('blogs::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $categories = BlogCategories::where('status', 'active')->orderBy('title')->get();
       // $tags = BlogTagMaster::where('status', 'active')->orderBy('name')->get();
        $editdata = Blogs::find($id);
        //$blogTags = BlogTags::where('blog_id', $id)->pluck('tag_id')->toArray();
        //return view('blogs::blogsmaster.edit', compact('categories', 'tags', 'editdata', 'blogTags'));
        return view('blogs::blogsmaster.edit', compact('categories', 'editdata'));
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
        $destorydata = Blogs::find($id);
        $response = [];
        if (isset($destorydata) && isset($destorydata->id)) {
            $destorydata->delete();
            $response = ['status' => true, "Message" => 'Record deleted successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }

    public function statusChange($id)
    {
        $statusdata = Blogs::find($id);
        $response = [];
        if (isset($statusdata) && isset($statusdata->status)) {
            if ($statusdata->status == 'active') {
                $statusdata->update([
                    'status' => 'inactive',
                ]);
            } else {
                $statusdata->update([
                    'status' => 'active',
                ]);
            }
            $response = ['status' => true, "Message" => 'Status updated successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
    }

    public function orderChange(Request $request)
    {
        $statusdata = Blogs::find($request->id);
        $response = [];
        if (isset($statusdata) && isset($statusdata->status)) {

            $statusdata->update([
                'order_by' => $request->order,
            ]);

            $response = ['status' => true, "Message" => 'Order updated successfuly', "data" => []];
        } else {
            $response = ['status' => false, "Message" => 'Record not found!', "data" => []];
        }
        return $response;
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
}
