<?php

namespace App\Http\Controllers\Api\v2;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as ImageInvertation;
use App\Http\Controllers\Controller;

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
                "small" => 100,
            ];
        } else if ($figure == 'comment') {
            $sizes = [
                "medium" => 400,
            ];
        } else {
            $sizes = [
                "large" => 900,
                // "medium" => 650,
                // "small" => 650,
            ];
        }

        $path = Storage::disk('public')->path("img/large");

        $source = $request->file('image');
        $resize = ImageInvertation::make($source->path());
        $resize->orientate();

        $w = $resize->width();
        $h = $resize->height();

        foreach ($sizes as $size => $dimension) {
            $path = Storage::disk('public')->path("img/{$size}");

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
                    ->save("{$path}/{$name}", 90);
            }

            $images[$size] = "img/{$size}/{$name}";
        }

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
}
