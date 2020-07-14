@component('mail::message')
# Join {{$event->title}}!

{{$sender->name}} is inviting you to join the event **{{$event->title}}**.

@component('mail::panel')
    {{ $event->description }}

    {{ $event->start_at->format('j F, Y H:i') }} - {{ $event->end_at->format('j F, Y H:i') }}
@endcomponent

You may create an account if you do not already have one.

@component('mail::button', ['url' => $joinUrl])
Join now
@endcomponent

*This invite is valid for {{ $tokenLifespan }} days.*

Thanks,<br>
{{ config('app.name') }}
@endcomponent
