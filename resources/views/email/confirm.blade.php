@extends('layouts.emails')
@section('content')
<div class="v-text-align" style="line-height: 180%; text-align: left; word-wrap: break-word;">
    <center><h1>Appnomu Email Verification</h1>
        <a href="http://appnomu.com/verify?c=e={{$email}}&c={{$code}}" style="border: #0000ee solid 1px; border-radius: 5px; color: black; text-decoration: none; background-color: bisque; height: 40px; display: inline-block; width: 120px; margin: 5px;" target="_blank">Verify My Email</a>
    </center>
    <p style="font-size: 14px; line-height: 180%; text-align: center;">
        <span style="font-size: 16px; line-height: 28.8px; font-family: Lato, sans-serif;">
            If the button above does not work please use the link below 
            <span style="text-decoration: underline; font-size: 16px; line-height: 28.8px;">
                <span style="color: #e67e23; font-size: 16px; line-height: 28.8px; text-decoration: underline;">http://appnomu.com/verify?e={{$email}}&c={{$code}} </span>
            </span>
            You can also chat with a real live human during our operating hours. They can answer questions about your account or help you with your account setup process.
        </span>
    </p>
</div>
@endsection