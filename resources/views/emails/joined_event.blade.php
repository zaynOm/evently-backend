<x-mail::message>
# Event Joined Successfully!

Hi {{ $user->full_name }},

Congratulations! You've successfully joined the event.

Here's a summary of what you've set up:

<x-mail::panel color="primary">
## {{ $event->title }}

- 📅 {{ $event->date }} at {{ $event->formated_time }}
- 📍 {{ $event->location }}
- 👥 {{ $event->capacity }}
- 🏷️ {{ $event->category->name }}
</x-mail::panel>

@php
$dateTime = new DateTime("$event->date $event->time");
$startDate = $dateTime->format('Ymd\THis\Z');
$endDate = $dateTime->modify('+1 hour')->format('Ymd\THis\Z');

$calendarLink = "https://calendar.google.com/calendar/u/0/r/eventedit?" .
    "text=" . urlencode($event->title) . 
    "&details=" . urlencode($event->description) .
    "&dates=$startDate/$endDate" . 
    "&location=" . urlencode($event->location);
@endphp

<x-mail::button :url="$calendarLink" color="primary">
Add to Google Calendar
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>