<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as ImageInvertation;

class ImageController extends Controller
{
    public function upload(Request $request, $figure = 'original')
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:5000',
        ]);

        $user = auth('sanctum')->user();

        $source = $request->file('image');
        $uuid = \Str::orderedUuid()->toString();
        $format = $figure == 'manifest'? 'png': $source->extension();
        $name = "{$uuid}.{$format}";

        if ($figure == 'manifest') {
            $sizes = [
                "full" => 512,
                "large" => 256,
                "medium" => 192,
                "small" => 32,
            ];
        } else {
            $sizes = [
                "full" => 1000,
                "large" => 600,
                "medium" => 300,
                "small" => 100,
            ];
        }

        $image = new Image;
        $image->user_id = $user->id;
        $image->name = $source->getClientOriginalName();
        $image->slug = $uuid;

        // ImageInvertation::configure(array('driver' => 'imagick'));
        $resize = ImageInvertation::make($source->path());

        foreach ($sizes as $size => $dimension) {
            $path = Storage::disk('public')->path("img/{$size}");
            if (!File::exists(dirname("{$path}/{$name}"))) {
                File::makeDirectory(dirname("{$path}/{$name}"), 0755, true, true);
            }

            $w = $resize->width();
            $h = $resize->height();

            if ($figure == 'original') {
                if ($w > $h) {
                    if ($dimension > $h) {
                        $dimension = $h;
                    }

                    $resize->resize(null, $dimension, function ($const) {
                        $const->aspectRatio();
                    })->save("{$path}/{$name}", 90);
                } else {
                    if ($dimension > $w) {
                        $dimension = $w;
                    }

                    $resize->resize($dimension, null, function ($const) {
                        $const->aspectRatio();
                    })->save("{$path}/{$name}", 90);
                }
            } else if (in_array($figure, ['rectangle', 'manifest'])) {
                $resize->fit($dimension)->save("{$path}/{$name}", 90, $format);
            }

            // \ImageOptimizer::optimize("{$path}/{$name}");

            $image->{$size} = "img/{$size}/{$name}";
        }

        $image->save();

        if ($request->block) {
            $image->blocks()->sync($request->block);
        }

        return response()->json([
            'status' => true,
            'image' => $image,
        ]);
    }
}
