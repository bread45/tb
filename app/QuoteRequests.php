<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuoteRequests extends Model
{
    protected $table = 'quote_requests';
    protected $fillable = [
        'user_id', 
        'event_type_id', 
        'event_type_other', 
        'location', 
        'event_date', 
        'estimated_time', 
        'estimated_duration', 
        'message', 
        'estimated_guests', 
        'your_role', 
        'what_stage', 
        'contact_no', 
        'archived', 
        'is_assigned',
        'main_type',
        'event_type_selection',
        'event_type_name',
        'event_type_website',
        'location_venue_type',
        'estimated_duration_type',
        'your_role_other',
    ];

    public function servicelist()
    {
        return $this->hasMany('App\QuoteRequestServices', 'quote_request_id', 'id');
    }
    public function multirequest()
    {
        return $this->hasMany('App\QuoteRequestConversation', 'quote_request_id', 'id');
    }
    public function eventtype()
    {
        return $this->hasOne('App\EventTypes', 'id', 'event_type_id');
    }
    public function QuoteRequestSupplier()
    {
        return $this->hasMany('App\QuoteRequestSupplier', 'quote_request_id', 'id');
    }
    public function QuoteRequestConversation()
    {
        return $this->hasMany('App\QuoteRequestConversation', 'quote_request_id', 'id');
    }
    public function quoteReviews()
    {
        return $this->hasOne('App\ReviewsMaster', 'quote_id', 'id');
    }
    public function quoteBy() {
        return $this->hasOne('Modules\Users\Entities\FrontUsers', 'id','user_id');
    }
    public function quoteRequestsStepValues() {
        return $this->hasMany('App\QuoteRequestsStepValue', 'quote_request_id','id');
    }
}
