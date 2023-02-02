<div class="block block-rounded overflow-hidden">
    <div class="block-content">
        <h4 class="mb-1">
            <a class="text-dark" href="{{ url("vacancy/{$vacancy->id}") }}">{{ Str::limit($vacancy->job_title, 85) }}</a>
        </h4>
        <p class="fs-sm fw-medium mb-2">
            <a href="{{ url("user/{$vacancy->user_id}") }}">{{ $vacancy->user->name }}</a> · {{ Date::parse($vacancy->created_at)->format('j F Y') }} <!-- · <span class="text-muted fs-xs"><i class="fa fa-eye"></i> {{ $vacancy->vacancy_view }}</span> -->
        </p>
        <p class="fs-sm text-muted">
            {!! Str::limit(strip_tags(html_entity_decode($vacancy->task)), 100) !!}
        </p>
    </div>
</div>