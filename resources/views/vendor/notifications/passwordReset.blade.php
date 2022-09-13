@component('mail::message')

# Password reset
<br>
Hi {{ $user->username }}, <br>
<br>
here you can reset your password. <br>
The button is only 60 minutes valid.  <br>

@component('mail::button', ['url' => $url, 'color' => 'primary'])
Set my new password
@endcomponent


Your <b>{{config('app.name')}}</b> Team



{{-- Subcopy --}}
@slot('subcopy')
  If the button is not working for you, you may copy the following URL and open it in the browser
  <span class="break-all">[{{ $url }}]({{ $url }})</span>
@endslot
@endcomponent
