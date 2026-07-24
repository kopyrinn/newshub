<?php

namespace App\Http\Controllers\Api\v2;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as ImageInvertation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UploadController extends Controller
{
    public function image(Request $request, $figure = 'rectangle')
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:15360',
        ]);

        $images = [];

        $uuid = \Str::orderedUuid()->toString();
        $name = $uuid . '.webp';

        if ($figure == 'rectangle') {
            $sizes = [
                "large" => 600,
            ];
        } else if ($figure == 'comment') {
            $sizes = [
                "medium" => 400,
            ];
        } else {
            $sizes = [
                "large" => 900,
            ];
        }

        $source = $request->file('image');
        $resize = ImageInvertation::make($source->path());
        $resize->orientate();

        $w = $resize->width();
        $h = $resize->height();

        foreach ($sizes as $size => $dimension) {
            $directory = "img/{$size}";
            Storage::disk('public')->makeDirectory($directory);
            $path = Storage::disk('public')->path($directory);

            if (in_array($figure, ['original', 'comment'])) {
                if ($w > $h) {
                    if ($dimension > $h) {
                        $dimension = $h;
                    }

                    $resize->resize(null, $dimension, function ($const) {
                        $const->aspectRatio();
                    });
                } else {
                    if ($dimension > $w) {
                        $dimension = $w;
                    }

                    $resize->resize($dimension, null, function ($const) {
                        $const->aspectRatio();
                    });
                }

                $resize->save("{$path}/{$name}", $size == 'small'? 1: 90);
            } else if ($figure == 'rectangle') {
                $resize->fit($dimension)
                    ->save("{$path}/{$name}", 100);
            }

            $images[$size] = "img/{$size}/{$name}";
        }

        $resize->destroy();

        $params = [
            'ok' => true,
            'images' => [],
        ];

        if (!empty($images["large"])) {
            $params['images']['lg'] = $images["large"];
        }

        if (!empty($images["medium"])) {
            $params['images']['md'] = $images["medium"];
        }

        if (!empty($images["small"])) {
            $params['images']['sm'] = $images["small"];
        }

        return response()->json($params);
    }

    public function upload(Request $request)
    {
        $validator = $this->validateRequest($request);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()]);
        }

        $imageFolder = config('nova-tinymce-editor.extra.upload_images.folder') ?? 'images';

        $uuid = \Str::orderedUuid()->toString();
        $name = $uuid . '.webp';

        $source = $request->file('file');
        if (!$source) {
            return response()->json(['error' => 'Failed to move uploaded file.']);
        }

        $resize = ImageInvertation::make($source->path());
        $resize->orientate();

        $w = $resize->width();
        $h = $resize->height();

        $dimension = 800;

        if ($w > $h) {
            if ($dimension > $h) {
                $dimension = $h;
            }

            $resize->resize(null, $dimension, function ($const) {
                $const->aspectRatio();
            });
        } else {
            if ($dimension > $w) {
                $dimension = $w;
            }

            $resize->resize($dimension, null, function ($const) {
                $const->aspectRatio();
            });
        }

        Storage::disk('public')->makeDirectory($imageFolder);
        $path = Storage::disk('public')->path($imageFolder);

        $resize->save("{$path}/{$name}", 95);

        return response()->json(['location' => config('app.url') . '/storage/' . $imageFolder . '/' . $name]);
    }

    public function validateRequest(Request $request): \Illuminate\Validation\Validator
    {
        $maxSize = config('nova-tinymce-editor.extra.upload_images.maxSize') ?? 2048;
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:'.$maxSize,
        ]);

        return $validator;
    }
}
