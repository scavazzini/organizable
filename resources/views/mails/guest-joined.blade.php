@component('mail::message')
# {{ $guest->name }} has joined!

{{ $guest->name }} has joined **{{ $event->title }}**.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
