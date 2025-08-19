<x-mail::message>
# Hola {{ $loan->user->name }}

Este es un recordatorio de que tu préstamo **{{ $loan->book->title }}**
vence mañana ({{ \Carbon\Carbon::parse($loan->fecha_vencimiento)->format('d/m/Y') }}
).

<x-mail::button :url="route('dashboard')">
Ver préstamo
</x-mail::button>

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
