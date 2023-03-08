@component('mail::message')

Hello,
<br />
<p>A new member has been registered to Deal Station. Against {{$user['email']}}.</p>
<br />
@component('mail::button', ['url' => $user['url']])
View Member
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