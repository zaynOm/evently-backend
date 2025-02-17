<x-mail::message>
# Event Created Successfully!

Hi {{ $user->full_name }},

Congratulations! You've successfully created a new event.

Here's a summary of what you've set up:

<x-mail::panel color="primary">
## {{ $event->title }}

- 📅 {{ $event->date }} at {{ $event->formated_time }}
- 📍 {{ $event->location }}
- 👥 {{ $event->capacity }}
- 🏷️ {{ $event->category->name }}
</x-mail::panel>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>