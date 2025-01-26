<x-mail::message>
# We have received your request to reset your account password

You can use the following code to recover your account:

<x-mail::panel>
{{ $code }}
</x-mail::panel>

The allowed duration of the code is one hour from the time the message was sent

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
