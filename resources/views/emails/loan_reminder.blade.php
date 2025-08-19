<x-mail::message>
# Hola {{ $loan->user->name }}

Este es un recordatorio de que tu préstamo **{{ $loan->book->title }}**
vence mañana ({{ $loan->fecha_vencimiento->format('d/m/Y') }}).

<x-mail::button :url="route('loans.show', $loan)">
Ver préstamo
</x-mail::button>

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
