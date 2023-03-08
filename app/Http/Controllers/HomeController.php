<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Users\Entities\FrontUsers;
use App\Countries;
use App\TrainerServices;
use Modules\Orders\Entities\Orders;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $services = TrainerServices::select('*', DB::raw('count(*) as count'))
                                    ->groupBy('service_id')
                                    ->with(['service','Orders'])
                                    ->get();
        
        $ordersData = Orders::with(['Ratting'])
                         ->get();
                         //dd($ordersData->sum('amount'));   
        $earnings = $ordersData->sum('amount');                             
                            $retingdata =   $ordersData->transform(function ($v) { 
                            if (isset($v->Ratting->rating)) {
                                return $v->Ratting->rating;
                                }
                            });

                            $retingdata = $retingdata->reject(function ($item) {
                                    return is_null($item);
                            });

                            $r = 0;
                            foreach ($retingdata as $rdata) {
                                $r += $rdata;
                            }
                            
                            if ($r != 0) {
                            $ratting = $r / $retingdata->count();
                            } else {
                            $ratting = 0;
                            }
                       
                            
     
                            $returnarray = array();
                            $array = array();
                           
                            foreach($services as $trainerService){
                                foreach ($trainerService->orders as $serviceEvent) {
                                    $date = explode("-", $serviceEvent->service_date); 
                        
                                    $startDate = strtotime($date[0]);
                                    $startDate = date('Y-m-d',$startDate);            
                                                    
                                    $endDate = strtotime(end($date).' +1 day');
                                    $endDate = date('Y-m-d',$endDate);
                        
                                    $time = explode("-", $serviceEvent->service_time); 
                                    $starttime = strtotime($time[0]);
                                    $starttime = date('H:i:s',$starttime);
                                   
                                    $array = array(
                                        'title' => $trainerService->service->name,
                                        'start' => $startDate.'T'.$starttime,
                                        'end' => $endDate,
                                        //'time' => $serviceEvent->service_time
                                        //'description' => '<b>Service Name : </b>' . $serviceEventsData->service->name . '<br><b>Start Date : </b>' . $startDate . '<br><b>End Date : </b>' . $endDate . '<br><b>Time : </b>' . $serviceEvent->service_time . '<br>',
                                    );
                                    $returnarray[] = $array;
                                }
                            }
                            
        return view('welcome',  [
                                                "services" => $services, 
                                                "reviewCount" => $retingdata->count(), 
                                                "ratting" => round($ratting,1),
                                                "ordersCount" => $ordersData->count(),
                                                "earnings" => $earnings,
                                                "eventData" => json_encode($returnarray),
                                            ]);

    }
}
