<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { background: #ffffff; color: #1f2937; font-size: 12px; margin: 0; padding: 28px 32px; }
        .header { border-bottom: 3px solid #2563eb; padding-bottom: 12px; margin-bottom: 20px; }
        .brand { font-size: 22px; font-weight: bold; color: #2563eb; letter-spacing: 0.5px; }
        .brand small { color: #6b7280; font-weight: normal; font-size: 11px; }
        h1 { font-size: 17px; margin: 20px 0 4px; }
        h2 { font-size: 14px; margin: 24px 0 8px; }
        .subtitle { color: #6b7280; font-size: 11px; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }
        .info td { padding: 5px 0; vertical-align: top; }
        .info td.label { color: #6b7280; width: 150px; }
        .info td.value { font-weight: bold; }
        .banner-preview { background: #f9fafb; border: 1px solid #e5e7eb; margin-top: 18px; padding: 12px; text-align: center; }
        .banner-preview img { display: block; margin: 0 auto; max-height: 150px; max-width: 100%; }
        .banner-caption { color: #6b7280; font-size: 9px; margin-top: 7px; }
        .metrics { margin-top: 18px; }
        .metrics th, .metrics td { border: 1px solid #e5e7eb; padding: 10px 12px; text-align: left; }
        .metrics th { background: #f3f4f6; color: #374151; font-size: 10px; text-transform: uppercase; }
        .metrics td.num { font-size: 18px; font-weight: bold; color: #111827; }
        .metrics td.num.blue { color: #2563eb; }
        .metrics td.num.green { color: #059669; }
        .daily th, .daily td { border: 1px solid #e5e7eb; padding: 7px 9px; text-align: right; }
        .daily th { background: #f3f4f6; color: #374151; font-size: 10px; text-transform: uppercase; }
        .daily th:first-child, .daily td:first-child { text-align: left; }
        .daily tbody tr:nth-child(even) { background: #f9fafb; }
        .daily tfoot td { background: #eff6ff; font-weight: bold; }
        .note { color: #6b7280; font-size: 9px; margin-top: 10px; }
        .footer { margin-top: 30px; color: #9ca3af; font-size: 9px; border-top: 1px solid #e5e7eb; padding-top: 9px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">NEWSHUB.KZ <small>· Информационный хаб Казахстана</small></div>
    </div>

    <h1>Отчёт по баннеру за период</h1>
    <div class="subtitle">
        {{ $from->format('d.m.Y') }} — {{ $to->format('d.m.Y') }}
        · {{ $rows->count() }} {{ trans_choice('день|дня|дней', $rows->count()) }}
    </div>

    <table class="info">
        <tr><td class="label">Баннер (ID)</td><td class="value">#{{ $ad->id }}</td></tr>
        <tr><td class="label">Позиция</td><td class="value">{{ $locationLabel }}</td></tr>
        <tr><td class="label">Ссылка</td><td class="value">{{ $ad->url }}</td></tr>
        <tr><td class="label">Период отчёта</td><td class="value">{{ $from->format('d.m.Y') }} — {{ $to->format('d.m.Y') }}</td></tr>
    </table>

    @if ($bannerDataUri)
        <div class="banner-preview">
            <img src="{{ $bannerDataUri }}" alt="Рекламный баннер #{{ $ad->id }}">
            <div class="banner-caption">Рекламный баннер, использованный в размещении</div>
        </div>
    @endif

    <table class="metrics">
        <tr>
            <th>Показы за период</th>
            <th>Клики за период</th>
            <th>CTR (кликабельность)</th>
        </tr>
        <tr>
            <td class="num">{{ number_format($views, 0, '.', ' ') }}</td>
            <td class="num blue">{{ number_format($clicks, 0, '.', ' ') }}</td>
            <td class="num green">{{ number_format($ctr, 2, ',', ' ') }}%</td>
        </tr>
    </table>

    <h2>Статистика по дням</h2>

    <table class="daily">
        <thead>
            <tr>
                <th>Дата</th>
                <th>Показы</th>
                <th>Клики</th>
                <th>CTR</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    <td>{{ $row['date']->format('d.m.Y') }}</td>
                    <td>{{ number_format($row['views'], 0, '.', ' ') }}</td>
                    <td>{{ number_format($row['clicks'], 0, '.', ' ') }}</td>
                    <td>{{ number_format($row['ctr'], 2, ',', ' ') }}%</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>Итого</td>
                <td>{{ number_format($views, 0, '.', ' ') }}</td>
                <td>{{ number_format($clicks, 0, '.', ' ') }}</td>
                <td>{{ number_format($ctr, 2, ',', ' ') }}%</td>
            </tr>
        </tfoot>
    </table>

    <div class="note">
        В таблице учитывается статистика, накопленная системой после запуска дневного учёта баннеров.
        Дни без зарегистрированных показов и кликов отображаются с нулевыми значениями.
    </div>

    <div class="footer">
        Сформировано {{ $generatedAt->format('d.m.Y H:i') }} · NewsHub.kz · Автоматический отчёт
    </div>
</body>
</html>
