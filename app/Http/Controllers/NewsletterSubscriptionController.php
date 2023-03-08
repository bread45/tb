<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\NewsletterSubscription;
use DataTables;
use Validator;
use Redirect;
use DB;



class NewsletterSubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.pages.newsletter_scbscribers.index');
    }


    public function getAll(){
        $data = NewsletterSubscription::latest()->groupBy('email')->get();
        return Datatables::of($data)->addIndexColumn()
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        
    }
}
