@php $appName = config('app.name') @endphp
<x-mail::message>
  # Welcome to {{ $appName }}!

  Hi {{ $user->full_name }},

  We're glad you're here.

  Your event planning journey starts now. We're excited to have you on board!

  <x-mail::button :url="config('app.url')" color="primary">
    Get Started
  </x-mail::button>

  Thanks,<br>
  {{ $appName }}
</x-mail::message>