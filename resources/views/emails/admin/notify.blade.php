@component('mail::message')
# Pending request

There is a pending request bla bla

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
