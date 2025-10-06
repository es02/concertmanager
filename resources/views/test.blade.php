<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body class="font-sans antialiased">
        <h2>Hello {{ $name }},<h2>
        <p>Thank you for applying for {{ $event }} as an {{ $applicationType }}.</p>
        <p>For your records here is a copy of your application:</p>
        <p> @foreach ($items as $item)
            {{ $item->name }} {{ $item->value }}<br/>
        @endforeach </p>
        <p>Please be assured that we take every application seriously, and we will let you know as soon as we have made a decision.</p>
        <p>Regards,<br/>
        The {{ $event }} organising team.</p>
    </body>
</html>
