@component('mail::message')

# Hello, you have some upcoming events<br>&nbsp;

@foreach($events as $event)
- **[{{$event->title}}]({{ url('/events/'.$event->id) }})**<br>
<small>*{{ $event->start_at->format('j F, Y H:i') }}*</small>

@endforeach

Thanks,<br>
{{ config('app.name') }}
@endcomponent
