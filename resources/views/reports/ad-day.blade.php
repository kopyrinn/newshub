<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { background: #ffffff; color: #1f2937; font-size: 13px; margin: 0; padding: 32px 36px; }
        .header { border-bottom: 3px solid #2563eb; padding-bottom: 14px; margin-bottom: 22px; }
        .brand { font-size: 22px; font-weight: bold; color: #2563eb; letter-spacing: 0.5px; }
        .brand small { color: #6b7280; font-weight: normal; font-size: 11px; }
        h1 { font-size: 17px; margin: 22px 0 4px; }
        .subtitle { color: #6b7280; font-size: 12px; margin-bottom: 18px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .info td { padding: 6px 0; vertical-align: top; }
        .info td.label { color: #6b7280; width: 160px; }
        .info td.value { font-weight: bold; }
        .banner-preview { background: #f9fafb; border: 1px solid #e5e7eb; margin-top: 20px; padding: 14px; text-align: center; }
        .banner-preview img { display: block; margin: 0 auto; max-height: 180px; max-width: 100%; }
        .banner-caption { color: #6b7280; font-size: 10px; margin-top: 8px; }
        .metrics { margin-top: 20px; }
        .metrics th, .metrics td { border: 1px solid #e5e7eb; padding: 12px 14px; text-align: left; }
        .metrics th { background: #f3f4f6; color: #374151; font-size: 12px; text-transform: uppercase; }
        .metrics td.num { font-size: 20px; font-weight: bold; color: #111827; }
        .metrics td.num.blue { color: #2563eb; }
        .metrics td.num.green { color: #059669; }
        .footer { margin-top: 40px; color: #9ca3af; font-size: 10px; border-top: 1px solid #e5e7eb; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">NEWSHUB.KZ <small>· Информационный хаб Казахстана</small></div>
    </div>

    <h1>Отчёт по баннеру за {{ $date->format('d.m.Y') }}</h1>
    <div class="subtitle">Статистика показов и кликов за один день</div>

    <table class="info">
        <tr><td class="label">Баннер (ID)</td><td class="value">#{{ $ad->id }}</td></tr>
        <tr><td class="label">Позиция</td><td class="value">{{ $locationLabel }}</td></tr>
        <tr><td class="label">Ссылка</td><td class="value">{{ $ad->url }}</td></tr>
        <tr><td class="label">Дата отчёта</td><td class="value">{{ $date->format('d.m.Y') }} ({{ $date->locale('ru')->dayName }})</td></tr>
    </table>

    @if ($bannerDataUri)
        <div class="banner-preview">
            <img src="{{ $bannerDataUri }}" alt="Рекламный баннер #{{ $ad->id }}">
            <div class="banner-caption">Рекламный баннер, использованный в размещении</div>
        </div>
    @endif

    <table class="metrics">
        <tr>
            <th>Показы</th>
            <th>Клики</th>
            <th>CTR (кликабельность)</th>
        </tr>
        <tr>
            <td class="num">{{ number_format($views, 0, '.', ' ') }}</td>
            <td class="num blue">{{ number_format($clicks, 0, '.', ' ') }}</td>
            <td class="num green">{{ $ctr }}%</td>
        </tr>
    </table>

    <table class="info" style="margin-top: 24px;">
        <tr><td class="label">Всего за период (все дни)</td><td class="value">{{ number_format($ad->views, 0, '.', ' ') }} показов · {{ number_format($ad->clicks, 0, '.', ' ') }} кликов</td></tr>
    </table>

    <div class="footer">
        Сформировано {{ $generatedAt->format('d.m.Y H:i') }} · NewsHub.kz · Автоматический отчёт
    </div>
</body>
</html>
