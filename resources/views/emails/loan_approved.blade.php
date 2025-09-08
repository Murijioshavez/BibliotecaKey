@php
    $user = $loan->user;
    $book = $loan->book;
@endphp

Hola {{ $user->name }},

¡Buenas noticias! Tu préstamo del libro "{{ $book->title }}" ha sido **aprobado.

📅 Fecha de vencimiento: {{ $loan->fecha_vencimiento->format('d/m/Y') }}

Recuerda pasar por la biblioteca para recoger tu ejemplar.

Gracias,
Biblioteca Digital
