<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Routing\Route;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Http\Controllers\ResourceStoreController;
use Laravel\Nova\Http\Requests\CreateResourceRequest;
use Laravel\Nova\Nova;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

if (function_exists('pcntl_async_signals')) {
    pcntl_async_signals(true);
    pcntl_signal(SIGALRM, static function (): void {
        throw new RuntimeException('The production Nova post smoke test exceeded 45 seconds.');
    });
    pcntl_alarm(45);
}

Nova::resourcesIn(app_path('Nova'));
config(['queue.connections.redis' => ['driver' => 'sync']]);

$admin = User::query()
    ->whereHas('roles', static fn ($query) => $query->where('slug', 'admin'))
    ->firstOrFail();
$category = Category::query()->firstOrFail();

auth()->login($admin);
auth('sanctum')->setUser($admin);

$image = UploadedFile::fake()->image('production-nova-signature-smoke.jpg', 800, 400);
$uploadedImagePath = 'img/large/' . $image->hashName();

$request = CreateResourceRequest::create('/nova-api/posts', 'POST', [
    'status' => '1',
    'image_caption' => 'NewsHub production smoke test',
    'translations_title_ru' => '[PRODUCTION NOVA SIGNATURE SMOKE TEST]',
    'translations_title_kk' => '',
    'translations_title_en' => '',
    'translations_summary_ru' => 'Транзакционный production smoke test.',
    'translations_summary_kk' => '',
    'translations_summary_en' => '',
    'translations_content_ru' => '<p>Временный транзакционный тест публикации.</p>',
    'translations_content_kk' => '',
    'translations_content_en' => '',
    'newshub_signature' => 'Самые свежие новости экономики, политики и культуры на наших страницах в {telegram}, {instagram} и мобильных приложениях на {android} и {ios}.',
    'append_newshub_signature' => '1',
    'selected_categories' => json_encode([$category->id => true], JSON_THROW_ON_ERROR),
    'selected_rubrics' => '{}',
    'keywords' => 'production-signature-smoke-test',
    'is_slider' => '0',
    'is_featured' => '0',
    'is_recommended' => '0',
    'is_breaking' => '0',
    'is_styled' => '0',
    'to_fcm' => '0',
    'to_telegram' => '0',
    'user' => (string) $admin->id,
    'user_trashed' => 'false',
    'author' => (string) $admin->id,
    'author_trashed' => 'false',
    'created_at' => now()->addDay()->toISOString(),
], [], [
    'image' => $image,
]);
$request->setContainer($app);
$request->setRedirector($app->make('redirect'));
$request->setUserResolver(static fn () => $admin);

$route = new Route('POST', 'nova-api/{resource}', []);
$route->bind($request);
$request->setRouteResolver(static fn () => $route);

DB::beginTransaction();
$generatedImagePaths = [$uploadedImagePath];

try {
    $response = $app->make(ResourceStoreController::class)($request);
    if ($response->getStatusCode() !== 201) {
        throw new RuntimeException('Unexpected Nova response status: ' . $response->getStatusCode());
    }

    $postId = $response->getData(true)['id'] ?? null;
    $post = Post::query()->findOrFail($postId);
    $content = $post->getTranslation('content', 'ru');
    $generatedImagePaths = array_merge($generatedImagePaths, array_filter([
        $post->getRawOriginal('image'),
        $post->getRawOriginal('image_md'),
        $post->getRawOriginal('image_sm'),
        $post->getRawOriginal('image_fit'),
        $post->getRawOriginal('image_blur'),
    ]));

    if (! str_contains($content, 'newshub-editorial-signature')) {
        throw new RuntimeException('The saved Nova post is missing the NewsHub signature marker.');
    }

    echo "Production Nova signature smoke test passed; database transaction rolled back.\n";
} finally {
    DB::rollBack();
    Storage::disk('public')->delete(array_values(array_unique($generatedImagePaths)));

    if (function_exists('pcntl_alarm')) {
        pcntl_alarm(0);
    }
}
