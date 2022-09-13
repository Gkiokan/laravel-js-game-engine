@component('mail::message')

# Welcome to {{config('app.name')}}
<br>
thanks for your registration. <br>
Please verify your account by clicking on the Button bellow. <br>

@component('mail::button', ['url' => $url, 'color' => 'primary'])
Verify me now.
@endcomponent

Your <b>{{config('app.name')}}</b> Team

{{-- Subcopy --}}
@slot('subcopy')
  If the button is not working for you, you may copy the following URL and open it in the browser
  <span class="break-all">[{{ $url }}]({{ $url }})</span>
@endslot
@endcomponent
