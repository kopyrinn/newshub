@if (session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        <p class="mb-0"><i class="fa fa-fw fa-check me-2"></i>{{ session('success') }}</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning alert-dismissible" role="alert">
        <p class="mb-0"><i class="fa fa-fw fa-check me-2"></i>{{ session('warning') }}</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible" role="alert">
        <p class="mb-0"><i class="fa fa-fw fa-exclamation-circle me-2"></i>{{ session('error') }}</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('status'))
    <div class="alert alert-success alert-dismissible" role="alert">
        <p class="mb-0"><i class="fa fa-fw fa-check me-2"></i>{{ session('status') }}</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif