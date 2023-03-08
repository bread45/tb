<?php 
namespace App\Http\Controllers;

use App\Http\Requests;

use App\Resource;
use App\Services;
use Modules\Users\Entities\FrontUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DynamicSitemapController extends Controller
{
    
    public function index(Request $r)
    {
       
        $resources = Resource::orderBy('id','desc')->get();
        return response()->view('sitemap', compact('resources'))
          ->header('Content-Type', 'text/xml');

    }

    public function singleroutes(Request $r)
    {
       
        return response()->view('sitemapAbout')->header('Content-Type', 'text/xml');

    }

    public function resourceDetail(Request $r)
    {
       
        $resources = Resource::orderBy('id','desc')->get();
        return response()->view('sitemapResourceDetail', compact('resources'))
          ->header('Content-Type', 'text/xml');

    }
    
    public function provider(Request $r)
    {
        //  $resources = DB::table('resource')->groupBy('name')->get();
        $resources = DB::table('front_users')->where('spot_description', '!=', null)->where('spot_description', '!=', "")->get();
        //  dd($resources);

      

        return response()->view('sitemapProvider', compact('resources'))
          ->header('Content-Type', 'text/xml');

    }

    public function blogDetail(Request $r)
    {
       
         $resources = DB::table('blogs')->get();
    // dd($resources);  

        return response()->view('sitemapBlogDetail', compact('resources'))
          ->header('Content-Type', 'text/xml');

    }
    public function explore(Request $r)
    {
       
      $location = FrontUsers::Where('user_role','trainer')
      ->groupBy('city', 'state')->get();
      $categories = Services::where('status', '=', 'active')->get();
        return response()->view('sitemapExplore', compact('location','categories'))
          ->header('Content-Type', 'text/xml');
    }
}