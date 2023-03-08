@component('mail::message')

Hello,
<br />
<p>A new offer has been added to Deal Station Family by {{$user['merchant_name']}} ({{$user['merchant_email']}}) with name of {{$user['offer_name']}} </p>
<br />
@component('mail::button', ['url' => $user['url']])
View Offer
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent