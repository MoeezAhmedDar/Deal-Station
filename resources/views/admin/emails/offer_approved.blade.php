@component('mail::message')

Hello {{$user['merchant_name']}},
<br />
<p>Your offer with name of {{$user['offer_name']}} has been approved.</p>
@component('mail::button', ['url' => $user['url']])
View
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