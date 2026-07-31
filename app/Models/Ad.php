<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    private const LOCATION_LABELS = [
        'post.view' => 'Страница новости — сразу после текста',
        'category.view' => 'Лента публикаций — между карточками новостей',
        'sidebar.view' => 'Правая колонка — между двумя блоками «Последние новости»',
        'sidebar2.view' => 'Правая колонка — внизу, после блоков «Последние новости»',
    ];

    private const LEGACY_LOCATION_LABELS = [
        'header' => 'Устаревшая позиция: верх сайта (сейчас не выводится)',
        'sidebar_alt' => 'Устаревшая позиция: дополнительная правая колонка (сейчас не выводится)',
    ];

    /**
     * @var  string
     */
    protected $table = 'ads';

    protected $casts = [
        'expired_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function locationOptions(): array
    {
        return self::LOCATION_LABELS;
    }

    public static function locationLabel(?string $location): string
    {
        if (! $location) {
            return '—';
        }

        if (isset(self::LOCATION_LABELS[$location])) {
            return self::LOCATION_LABELS[$location];
        }

        if (str_starts_with($location, 'home.')) {
            return 'Устаревшая позиция: блок раздела «'.substr($location, 5).'» на главной (сейчас не выводится)';
        }

        return self::LEGACY_LOCATION_LABELS[$location] ?? $location;
    }

    public function stats()
    {
        return $this->hasMany(AdStat::class)->orderByDesc('date');
    }
}
