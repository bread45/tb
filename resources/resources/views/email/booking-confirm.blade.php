<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>

<body style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #f5f8fa; color: #74787E; height: 100%; hyphens: auto; line-height: 1.4; margin: 0; -moz-hyphens: auto; -ms-word-break: break-all; width: 100% !important; -webkit-hyphens: auto; -webkit-text-size-adjust: none; word-break: break-word;">
    <style>
        @media only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }

            .footer {
                width: 100% !important;
            }
        }

        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
    </style>
    <?php //dd($order); ?>
    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #f5f8fa; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
        <tr>
            <td align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                <table class="content" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                    <tr>
                        <td class="header" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 25px 0; text-align: center; background: #282a3c;">
                            <a href="{{url('/') }}" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #bbbfc3; font-size: 19px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 white;">
                                <img src="{{ asset('images/logo.png') }}" alt="img" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border: none; max-width: 150px;">
                            </a>
                        </td>
                    </tr>
                    <!-- Email Body -->
                    <tr>
                        <td class="body" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #FFFFFF; border-bottom: 1px solid #EDEFF2; border-top: 1px solid #EDEFF2; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                            <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #FFFFFF; margin: 0 auto; padding: 0; width: 570px; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px;">
                                <!-- Body content -->
                                <tr>
                                    <td class="content-cell" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 35px;">
                                        <h1 style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #2F3133; font-size: 19px; font-weight: bold; margin-top: 0; text-align: left;">
                                        Your Booking has been confirmed!
                                        </h1>
                                        
                                        <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">
                                        Hi {{$order->first_name}} {{$order->last_name}}, <br/>
                                        Booking confirmed with our trainer. Please find the details below.
                                        </p>
                                        
                                        <table class="subcopy" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">
                                            <tr>
                                                <th>Trainer Name : </th>
                                                <td>{{$order->trainer->first_name}}</td>
                                            </tr>
                                            <tr>
                                                <th>Trainer Address :</th>
                                                <td>{{$order->trainer->address_1}} <br/>
                                                    {{$order->trainer->city}}
                                                    {{$order->trainer->state}}
                                                    {{$order->trainer->country}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Trainer Phone :</th>
                                                <td>{{$order->trainer->phone_number}} </td>
                                            </tr>
                                            <tr>
                                                <th>Service Name :</th>
                                                <td>{{$order->service->name}}</td>
                                            </tr>
                                            @if($order->stripe_payment_id)
                                            <tr>
                                                <th>Booking Dates :</th>
                                                <td>{{$order->start_date}} - {{$order->end_date}}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <th>Booking Time :</th>
                                                <td>{{$order->service_time}}</td>
                                            </tr>
                                            
                                        </table>
                                        <br>
                                        @if($order->stripe_payment_id)
                                        <table class="subcopy" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">   
                                            <tr>
                                                <th>Payment Details</th>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <th>Service Charges :</th>
                                                <td>${{$order->service->price}} USD</td>
                                            </tr>
                                           
                                             <tr>
                                                <th>Referral Discount:</th>
                                                <td>
                                                @if($order->ref_discount)
                                                ${{$order->ref_discount}} USD
                                                @else
                                                -
                                                @endif
                                                </td>
                                            </tr> 
                                            <tr>
                                                <th>Total Amount:</th>
                                                <td>${{$order->amount}} USD</td>
                                            </tr>
                                        </table>
                                        <br/>
                                        @endif
                                        @if($order->stripe_subscription_id)
                                        <table class="subcopy" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">   
                                            <tr>
                                                <th>Subscription Details</th>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <th>Subscription Charges :</th>
                                                <td>
                                                @if($order->plan_type == "monthly")
                                                ${{$order->service->price_monthly}} USD Monthly
                                                @endif
                                                @if($order->plan_type == "weekly")
                                                ${{$order->service->price_weekly}} USD Weekly
                                                @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Subscription Start Date :</th>
                                                <td>{{$order->start_date}}</td>
                                            </tr>   
                                        </table>
                                        <br/>
                                        @endif
                                        <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">
                                            Thank You for your interest! <br/>
                                            Regards,<br>The Training Block
                                        </p>
                                        <br>
                                        <table class="subcopy" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-top: 1px solid #EDEFF2; margin-top: 25px; padding-top: 25px;">
                                            <tr>
                                                <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                                    <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; line-height: 1.5em; margin-top: 0; text-align: left; font-size: 12px;">If you having trouble.
                                                        into your web browser:
                                                        <a href="{{url('/login')}}" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #3869D4;">Login</a>
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr style="background: #282a3c;">
                        <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                            <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0 auto; padding: 0; text-align: center; width: 570px; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px;">
                                <tr>
                                    <td class="content-cell" align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 35px;">
                                        <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; color: #AEAEAE; font-size: 12px; text-align: center;">© {{date('Y')}} Training Block. All rights reserved.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>