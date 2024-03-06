Hello {{ $user ->name}},
<h1>Welcome in ZaHa Script.</h1>
<p>We understand it happens.</p>
@component('mail::button', ['url' => url('reset/'.$user->remember_token)])
Reset Your Password
@endcomponent
<p>In case you have any issues recovering your password, please contact us.</p>
Thanks,<br>
