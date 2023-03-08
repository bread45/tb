<?php

namespace App\Http\Controllers;

use App\BookingHistory;
use DataTables;
use App\Feature;
use App\Equipment;
use App\User;
use App\BookingCompletation;
use Auth;
use Illuminate\Http\Request;
use App\SystemLog;
use PDF;

class AdminBookingHistoryController extends Controller {

    public function __construct() {
        $this->middleware('auth:admin');
    }

    /**/

    public function bookingcancel(Request $request) {
        $response['success'] = false;
        $response['title'] = "Warning";
        $response['type'] = "error";
        $response['msg'] = "Booking can't canceled, please try again";
        $input = $request->all();
        try {
            $laststatus = BookingHistory::where('id', $input['booking_history_id'])->first();
            $user_id = $laststatus->supplier_id;
            $rental_id = $laststatus->rental_id;
            $laststatus = $laststatus->stage;
            $data['user_id'] = $user_id;
            $data['booking_history_id'] = $input['booking_history_id'];
            $data['stage'] = 'request_cancel';
            $data['status'] = '1';
            $data['status_order'] = '2';
            $count = BookingCompletation::where([['booking_history_id', $input['booking_history_id']], ['stage', 'request_cancel']])->count();
            if ($count == 0) {
                $cancel = new BookingCompletation($data);
                $cancel->save();
                $bookinghistory = BookingHistory::find($input['booking_history_id'])->update(['current_status' => 'request_cancel']);
                $SystemLog = SystemLog::create(['done_by' => $rental_id, 'done_with' => $user_id, 'reference_id' => $input['booking_history_id'], 'type' => 'request_cancel', 'admin_read' => 0, 'rental_read' => 0, 'supplier_read' => 0, 'notification' => getconstants('message', 'request_cancel')]);
                sentnotificationtodevice($SystemLog->id);
            }

            if ($request->ajax()) {
                $msg = 'Your booking has successfully been canceled';
                $response['success'] = true;
                $response['title'] = "Success";
                $response['type'] = "success";
                $response['msg'] = $msg;
            } else {
                return redirect()->route('allbookinghistory.show', ['id' => $input['booking_history_id']]);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $msg = $ex->getMessage();
            if (isset($ex->errorInfo[2])) :
                $msg = $ex->errorInfo[2];
            endif;

            if ($request->ajax()) {
                $response['msg'] = $msg;
            } else {
                return redirect()->route('allbookinghistory.show', ['id' => $input['booking_history_id']])->withInput($request->all())->with('error', $msg);
            }
        } catch (Exception $ex) {
            $msg = $ex->getMessage();
            if (isset($ex->errorInfo[2])) :
                $msg = $ex->errorInfo[2];
            endif;

            if ($request->ajax()) {
                $response['msg'] = $msg;
            } else {
                return redirect()->route('allbookinghistory.show', ['id' => $input['booking_history_id']])->withInput($request->all())->with('error', $msg);
            }
        }
        if ($request->ajax()) {
            return \Response::json($response);
        }
    }

    public function bookingcompletion(Request $request) {
        $input = $request->all();
        $next_step = get_next_step($input['booking_history_id']);
        $laststatus = BookingHistory::where('id', $input['booking_history_id'])->first();
        $rental_id = $laststatus->rental_id;
        $user_id = $laststatus->supplier_id;
        try {
            $data['user_id'] = $user_id;
            $data['booking_history_id'] = $input['booking_history_id'];
            $data['stage'] = $next_step['type'];
            $data['status'] = '1';
            $data['status_order'] = $next_step['order'];
            $count = BookingCompletation::where([['booking_history_id', $input['booking_history_id']], ['stage', $next_step['type']]])->count();
            if ($count == 0) {
                $cancel = new BookingCompletation($data);
                $cancel->save();
                $bookinghistory = BookingHistory::find($input['booking_history_id'])->update(['current_status' => $next_step['type']]);
                SystemLog::create(['done_by' => $user_id, 'done_with' => $rental_id, 'reference_id' => $input['booking_history_id'], 'type' => $next_step['type'], 'admin_read' => 0, 'rental_read' => 0, 'supplier_read' => 0, 'notification' => getconstants('message', $next_step['type'])]);
                $SystemLog = sentnotificationtodevice($SystemLog->id);
            }

            return redirect()->route('allbookinghistory.show', ['id' => $input['booking_history_id']]);
        } catch (\Illuminate\Database\QueryException $ex) {
            $msg = $ex->getMessage();
            if (isset($ex->errorInfo[2])) :
                $msg = $ex->errorInfo[2];
            endif;

            return redirect()->route('allbookinghistory.show', ['id' => $input['booking_history_id']])->withInput($request->all())->with('error', $msg);
        } catch (Exception $ex) {
            $msg = $ex->getMessage();
            if (isset($ex->errorInfo[2])) :
                $msg = $ex->errorInfo[2];
            endif;

            return redirect()->route('allbookinghistory.show', ['id' => $input['booking_history_id']])->withInput($request->all())->with('error', $msg);
        }
    }

    public function index() {
        return view('admin.bookinghistory.index');
    }

    public function show($id) {
        $booking_history = BookingHistory::where('id', $id)->first();
        $booking_history_status_array = $booking_history->bookingcompletation->pluck('stage')->toArray();
        $deliveredtosite = 'no';
        $canceledbooking = 'no';
        $history = BookingCompletation::where([['booking_history_id', $id], ['stage', 'delivered_site']])->count();
        if ($history > 0) {
            $deliveredtosite = 'yes';
        }

        $rehistory = BookingCompletation::where([['booking_history_id', $id], ['stage', 'request_cancel']])->count();
        if ($rehistory > 0) {
            $canceledbooking = 'yes';
        }
        return view('admin.bookinghistory.view', compact('laststatus', 'booking_history', 'deliveredtosite', 'canceledbooking', 'booking_history_status_array'));
    }

    public function create() {
        return view('admin.bookinghistory.create');
    }

    public function getall(Request $request) {

        $bookinghistory = BookingHistory::orderBy('booking_date', 'DESC')->get();

        return DataTables::of($bookinghistory)
                        ->addColumn('equipmentname', function ($q) {
                            return $q->supplierequipment->equipment_model->equipment->name;
                        })
                        ->addColumn('model', function ($q) {
                            return $q->supplierequipment->equipment_model->model_name;
                        })
                        ->addColumn('suppliername', function ($q) {
                            return $q->supplier->firstname . ' ' . $q->supplier->lastname;
                        })
                        ->addColumn('rentalname', function ($q) {
                            return $q->rental->firstname . ' ' . $q->rental->lastname;
                        })
                        ->addColumn('status', function ($q) {
                            return '<img src="' . url('front/images/' . getconstants('icon', $q->current_status)) . '" class="tbl_icon"  alt="display name"> &nbsp;' . getconstants('displayname', $q->current_status) . '';
                        })
                        ->addColumn('bookingdate', function ($q) {
                            return date("Y-m-d H:i:s", strtotime($q->booking_date));
                        })
                        ->addColumn('startdate', function ($q) {
                            return date("d/m/Y", strtotime($q->booking_start_date));
                        })
                        ->addColumn('enddate', function ($q) {
                            return date("d/m/Y", strtotime($q->booking_end_date));
                        })
                        ->addColumn('action', function ($q) {
                            $action_str = '';
                            $action_str .= '<a href="allbookinghistory/' . $q->id . '" title="View"><i class="fa fa-eye"></i></a>';
                            if (isset($q->jobcart->id)) {
                                $jobcart_edit = route('supplier-job-cart.edit', ['id' => key_encode($q->id)]);
                                $action_str .= ' | <a href="' . $jobcart_edit . '" title="Job Cart"><i class="fa fa-shopping-bag"></i></a>';
                            }
                            return $action_str;
                        })
                        ->addIndexColumn()
                        ->rawColumns(['action', 'status'])->make(true);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function pdf_invoice(Request $request, $id) {
        $bookinghistory = BookingHistory::with('supplier', 'rental')->whereId($id)->first();
        $pdf = PDF::loadView('front.invoice.invoice', compact('bookinghistory'))->setPaper('a4', 'portrait')->setWarnings(false);
        return $pdf->download('Order_' . $id . '.pdf');
//        return view('front.invoice.invoice',compact('bookinghistory'));
    }

    public function bookingrequest() {
        return view('admin.bookingrequest.index');
    }

    public function adminbookingrequestdata(Request $request) {

        $bookinghistory = BookingHistory::whereIn('current_status', array('request', 'accepted', 'request_cancel'))->orderBy('id', 'DESC')->get();

        return DataTables::of($bookinghistory)
                        ->addColumn('bookingid', function ($q) {
                            return $q->id;
                        })
                        ->addColumn('equipmentname', function ($q) {
                            return $q->supplierequipment->equipment_model->equipment->name;
                        })
                        ->addColumn('model', function ($q) {
                            return $q->supplierequipment->equipment_model->model_name;
                        })
                        ->addColumn('suppliername', function ($q) {
                            return $q->supplier->firstname . ' ' . $q->supplier->lastname;
                        })
                        ->addColumn('rentalname', function ($q) {
                            return $q->rental->firstname . ' ' . $q->rental->lastname;
                        })
                        ->addColumn('status', function ($q) {
                            return '<img src="' . url('front/images/' . getconstants('icon', $q->current_status)) . '" class="tbl_icon"> &nbsp;' . getconstants('displayname', $q->current_status) . '';
                        })
                        ->addColumn('bookingdate', function ($q) {
                            return date("d/m/Y", strtotime($q->booking_date));
                        })
                        ->addColumn('startdate', function ($q) {
                            return date("d/m/Y", strtotime($q->booking_start_date));
                        })
                        ->addColumn('enddate', function ($q) {
                            return date("d/m/Y", strtotime($q->booking_end_date));
                        })
                        ->addColumn('action', function ($q) {
                            $action_str = '';
                            $action_str .= '<a href="allbookinghistory/' . $q->id . '" title="View"><i class="fa fa-eye"></i></a>';
                            return $action_str;
                        })
                        ->addIndexColumn()
                        ->rawColumns(['action', 'status'])->make(true);
    }

    public function getallinvoice(Request $request) {
        $booking_id = $request->equiment_id;
        $data['booking_history'] = BookingHistory::where('id', $booking_id)->first();

        $invoice_query = \App\Bookingbilling::select('booking_billing.id', 'booking_billing.booking_id', 'booking_billing.billing_start_date', 'booking_billing.billing_end_date', 'booking_billing.amount', 'booking_billing.current_status', 'equipment.name as equipment_name', 'equipment_models.model_name', 'supplier_equipments.fleet_number');
        $invoice_query->leftjoin('booking_histories', 'booking_billing.booking_id', '=', 'booking_histories.id');
        $invoice_query->leftjoin('supplier_equipments', 'booking_histories.supplier_equipments_id', '=', 'supplier_equipments.id');
        $invoice_query->leftjoin('equipment_models', 'supplier_equipments.equipment_model_id', '=', 'equipment_models.id');
        $invoice_query->leftjoin('equipment', 'equipment_models.equipment_id', '=', 'equipment.id');
        $invoice_query->orderby('billing_start_date', 'asc');
        $invoice_query->orderby('booking_billing.booking_id', 'asc');
        $invoice_query->where('booking_billing.booking_id', $booking_id);
        $data['invoice_result'] = $invoice_query->get();
        if (isset($request->type)) {
            return view('admin.bookinghistory.get-booking-invoice-view', $data);
        }
        return view('front.admin.mybooking.get-booking-invoice-view', $data);
    }

    public function saveinvoiceid(Request $request) {
        $response['success'] = true;
        $response['msg'] = 'Invoice ID Save Successfully';
        if ($request->table == 'billing') {
            $invoice_query = \App\Bookingbilling::where('id', $request->invoice_id)->first();
            if (!empty($invoice_query)) {
                $invoice_query_data = \App\Bookingbilling::where('invoice_id', $request->invoice_data)->where('id', '!=', $request->invoice_id)->first();
                if (empty($invoice_query_data)) {
                    $invoice_query->invoice_id = $request->invoice_data;
                    $invoice_query->save();
                } else {
                    $response['success'] = false;
                    $response['msg'] = 'Invoice ID already exists. Please choice another.';
                }
            }
        } else {
            $invoice_query = \App\BookingHistory::where('id', $request->invoice_id)->first();
            if (!empty($invoice_query)) {
                $invoice_query_data = \App\BookingHistory::where('invoice_id', $request->invoice_data)->where('id', '!=', $request->invoice_id)->first();
                if (empty($invoice_query_data)) {
                    $invoice_query->invoice_id = $request->invoice_data;
                    $invoice_query->save();
                } else {
                    $response['success'] = false;
                    $response['msg'] = 'Invoice ID already exists. Please choice another.';
                }
            }
        }

        return \Response::json($response);
    }

}
