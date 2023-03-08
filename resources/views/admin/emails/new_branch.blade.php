@component('mail::message')

Hello,
<br />
<p>A new branch has been added to Deal Station Family by {{$user['merchant_name']}} ({{$user['merchant_email']}}) with name of {{$user['branch_name']}} ({{$user['branch_email']}}) </p>
<br />
@component('mail::button', ['url' => $user['url']])
View Branch
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent