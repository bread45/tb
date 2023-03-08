<div class="modal-header">
    <h5 class="modal-title contact_title" id="contact_title">View Message</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <table class="table data-table table-bordered table-hover">
        <tr>
            <th>Name</th>
            <td>
                {{(isset($contactus->name)) ? $contactus->name : '-'}}
            </td>
        </tr>
        <tr>
            <th>Email</th>
            <td>
                {{(isset($contactus->email)) ? $contactus->email : '-'}}
            </td>
        </tr>
        <tr>
            <th>Phone Number</th>
            <td>
                {{(isset($contactus->phone_number)) ? $contactus->phone_number : '-'}}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p class="contact_message">{{$contactus->message}}</p>
            </td>
        </tr>
    </table>
</div>
<!--<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>-->