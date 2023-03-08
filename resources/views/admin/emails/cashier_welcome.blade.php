@component('mail::message')

Hello {{$user['name']}},
<br />
<p>Welcome to the Deal Station Family. Let’s grow together!</p>
<br />
<p>Kindly find below your credentials to access your cashier app and begin the Deal Station journey:</p>
<br />
Email: {{$user['email']}}
<br />
Password: {{$user['password']}}
<br />
<p>Make sure to change your password after you access the app for the first time!</p>
<br />
@component('mail::button', ['url' => $user['url']])
Login
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