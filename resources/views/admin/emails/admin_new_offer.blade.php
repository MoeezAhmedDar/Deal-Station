@component('mail::message')

Hello {{$user['merchant_name']}},

<br />
<p>A new offer has been added to Deal Station Family for {{$user['merchant_name']}} ({{$user['merchant_email']}}) with name of {{$user['offer_name']}} </p>
<br />

@component('mail::button', ['url' => $user['url']])
View Offer
@endcomponent
<p>Contact us for any required assistance:</p>
{{$settings['app_email']}}
<br />
{{$settings['app_phone']}}
<br />
{{$settings['app_facebook']}}
<br />
{{$settings['app_insta']}}
<br />
{{$settings['app_twitter']}}
<br />
{{$settings['app_pinterest']}}
<br />
Thanks,<br>
{{ config('app.name') }}
@endcomponent