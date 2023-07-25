@component('emails.message')

{!! $content !!}

@lang('Regards'),<br>
{{ config('app.name') }}
<br>
<p class="sub">@lang('If you do not want to receive such emails, click the') <a href="{{ $unsubscribeUrl }}" target="_blank" rel="noopener">@lang('Unsubscribe')</a></p>

{{-- Subcopy --}}
@isset($actionText)
@slot('subcopy')
@lang(
    "If you're having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
    'into your web browser:',
    [
        'actionText' => $actionText,
    ]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
@endslot
@endisset
@endcomponent
