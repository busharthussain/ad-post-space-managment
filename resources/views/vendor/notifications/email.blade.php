@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level == 'error')
# {{_lang('Whoops!')}}
@else
# {{_lang('Kære administrator!')}}
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
            $color = 'green';
            break;
        case 'error':
            $color = 'red';
            break;
        default:
            $color = 'blue';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
{{_lang('Med venlige (Dele) Hilsener,')}}<br>Team Sharepeeps
@endif

{{-- Subcopy --}}
@isset($actionText)
@component('mail::subcopy')
    {{_lang('If you’re having trouble clicking the ')}}"{{ $actionText }}" {{_lang('button, copy and paste the URL below into your web browser:')}} [{{ $actionUrl }}]({{ $actionUrl }})
@endcomponent
@endisset
@endcomponent
