@php
    $stats = $user->posts()
        ->selectRaw('
            COUNT(*) as cnt,
            YEAR(created_at) as year,
            MONTH(created_at) as month
        ')
        ->where('status', 1)
        ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
        ->get();

    $chartjs = false;

    if ($stats->count() > 1) {
        $labels = [];
        foreach ($stats as $row) {
            $labels[] = \Format::mb_ucfirst(\Date::parse("{$row->year}-{$row->month}-01")->format('F Y'), 'UTF-8');
        }

        $chartjs = app()->chartjs
            ->name('lineChartTest')
            ->type('line')
            ->size(['width' => 400, 'height' => 90])
            ->labels($labels)
            ->datasets([
                [
                    'label' => __("Total Posts"),
                    'fill' => true,
                    'backgroundColor' => "rgba(101, 163, 13, .15)",
                    'borderColor' => "transparent",
                    'pointBackgroundColor' => "rgba(101, 163, 13, 1)",
                    'pointBorderColor' => "#fff",
                    'pointHoverBackgroundColor' => "#fff",
                    'pointHoverBorderColor' => "rgba(101, 163, 13, 1)",
                    'data' => $stats->pluck('cnt')->toArray()
                ]
            ])
            ->options([
                'maintainAspectRatio' => false,
                'tension' => 0.4,
                'scales' => [
                    'x' => [
                        'display' => false
                    ],
                    'y' => [
                        'display' => false
                    ]
                ],
                'interaction' => [
                    'intersect' => false
                ],
                'elements' => [
                    'point' => [
                        'radius' => 0,
                    ],
                ],
                'plugins' => [
                    'legend' => [
                        'display' => false
                    ],
                ]
            ]);
    }
@endphp

@if (!auth()->guest() && auth()->user()->id == $user->id && auth()->user()->isPress())
    <div class="block block-rounded block-fx-shadow">
        <div class="block-header block-header-default">
            <h3 class="block-title">{{ __("My Package") }}</h3>
        </div>
        <div class="block-content">
            @if ($user->packageActive())
                <h6 class="mb-2">{{ $user->package->name }}</h6>
                <p class="text-muted fs-sm">{{ __("Expired At") }}: {{ Date::parse($user->package_expired_at)->format('j F Y') }}</p>

                <h6 class="mb-2">{{ __("Limits") }}:</h6>
                <div class="mb-3">
                    <p class="text-muted fs-sm mb-0">{{ __("Press Release") }}: {{ $user->package_press }}</p>
                    <p class="text-muted fs-sm mb-0">{{ __("Events") }}: {{ $user->package_events }}</p>
                    <p class="text-muted fs-sm mb-0">{{ __("Vacancies") }}: {{ $user->package_vacancies }}</p>

                    @if ($user->package->slug !== 'standart')
                        <p class="text-muted fs-sm mb-0">{{ __("Translation Helps") }}: {{ $user->package_help }}</p>
                    @endif

                    @if ($user->package->slug == 'standart-maximum')
                        <p class="text-muted fs-sm mb-0">{{ __("Translations") }}: {{ $user->package_translate }}</p>
                        <p class="text-muted fs-sm mb-0">{{ __("PR") }}: {{ $user->package_pr }}</p>
                    @endif
                </div>
            @else
                <p class="text-center">{{ __("Not found active package") }}<br/><a href="{{ url('packages') }}">{{ __('Select') }}</a></p>
            @endif
        </div>
    </div>
@endif

@if ($user->posts()->count())
    <div class="block block-rounded block-fx-shadow d-flex flex-column">
        <div class="block-header block-header-default">
            <h3 class="block-title">{{ __("Statistic") }}</h3>
        </div>
        <div class="block-content flex-grow-1 d-flex justify-content-between align-items-center">
            <dl class="mb-0">
                <dt class="fs-3 fw-bold">{{ $user->posts()->where('status', 1)->sum('pageviews') }}</dt>
                <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">{{ __('Total Post Views') }}</dd>
            </dl>
            <div class="item item-rounded-lg bg-body-light">
                <i class="fa fa-eye fs-3 text-primary"></i>
            </div>
        </div>

        <div class="block-content flex-grow-1 d-flex justify-content-between">
            <dl class="mb-0">
                <dt class="fs-3 fw-bold">{{ $user->posts()->where('status', 1)->count() }}</dt>
                <dd class="fs-sm fw-medium text-muted mb-0">{{ __('Total Posts') }}</dd>
            </dl>
            <div class="item item-rounded-lg bg-body-light">
                <i class="fa fa-book-open fs-3 text-primary"></i>
            </div>
        </div>
        @if ($chartjs)
            <div class="block-content p-1 text-center overflow-hidden">
                {!! $chartjs->render() !!}
            </div>
        @else
            <div class="mb-3"></div>
        @endif
    </div>
@endif

@if (!auth()->guest())
    <div class="block block-rounded block-fx-shadow">
        <div class="block-header block-header-default">
            <h3 class="block-title">{{ __("Contacts") }}</h3>
        </div>
        <div class="block-content">
            <table class="table table-borderless fs-sm">
                <tbody>
                    <tr>
                        <td>
                        <i class="fa fa-fw fa-at me-1"></i> <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                        </td>
                    </tr>
                    @if ($user->phone)
                        <tr>
                            <td>
                            <i class="fa fa-fw fa-phone me-1"></i> {{ $user->phone }}
                            </td>
                        </tr>
                    @endif
                    @if ($user->address)
                        <tr>
                            <td>
                            <i class="fa fa-fw fa-address-card me-1"></i> {{ $user->address }}
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endif


@if (!auth()->guest() && (auth()->user()->id == $user->id || auth()->user()->isAdmin() || auth()->user()->isModerator()) && auth()->user()->isPress())
    <div class="block block-rounded block-fx-shadow">
        <div class="block-header block-header-default">
            <h3 class="block-title">{{ __("Requisites") }}</h3>
        </div>
        <div class="block-content">
            <p>{{ __("Bin") }}:  {{ $user->bin }}</p>
            <p>{{ __("Iban") }}:  {{ $user->iban }}</p>
            <p>{{ __("Bank") }}:  {{ $user->bank }}</p>
            <p>{{ __("Bik") }}:  {{ $user->bik }}</p>
            <p>{{ __("Kbe") }}:  {{ $user->kbe }}</p>
        </div>
    </div>
@endif

<div class="block block-rounded block-fx-shadow">
    <div class="block-header block-header-default">
        <h3 class="block-title">{{ __("Subscriptions") }}</h3>
        <div class="block-options">
            <div class="block-options-item">
                <span class="badge bg-primary">{{ $user->feeds()->count() }}</span>
            </div>
        </div>
    </div>
    <div class="block-content">
        @if ($user->feeds()->exists())
            <div class="d-flex-user">
                @foreach($user->feeds as $feedUser)
                    <a href="{{ url("user/{$feedUser->id}") }}"><img class="img-avatar img-avatar32 mb-3 me-2" src="{{ $feedUser->getAvatar() }}"/></a>
                @endforeach
            </div>
        @else
            <p class="text-center">{{ __("No Data") }}</p>
        @endif
    </div>
</div>
<div class="block block-rounded block-fx-shadow">
    <div class="block-header block-header-default">
        <h3 class="block-title">{{ __("Followers") }}</h3>
        <div class="block-options">
            <div class="block-options-item">
                <span class="badge bg-primary">{{ $user->followers()->count() }}</span>
            </div>
        </div>
    </div>
    <div class="block-content">
        @if ($user->followers()->exists())
            <div class="d-flex-user">
                @foreach($user->followers as $followerUser)
                    <a href="{{ url("user/{$followerUser->id}") }}"><img class="img-avatar img-avatar32 mb-3 me-2" src="{{ $followerUser->getAvatar() }}"/></a>
                @endforeach
            </div>
        @else
            <p class="text-center">{{ __("No Data") }}</p>
        @endif
    </div>
</div>