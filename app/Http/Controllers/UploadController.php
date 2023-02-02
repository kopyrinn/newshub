<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function image(Request $request)
    {
        $valid = ['jpeg', 'jpg', 'png', 'gif'];
        $maxSize = 5 * 1024;

        if( $request->hasFile('upload') && $request->file('upload')->isValid()) {
            $fSize = $request->file('upload')->getSize() / 1024;

            if( $fSize > $maxSize ) {
                return response()->json(['error' => ['message' => 'Максимальный размер файла ' . (ceil($maxSize / 1024)) . 'Мб']]);
            }

            if(!in_array($request->file('upload')->extension(), $valid)) {
                return response()->json(['error' => ['message' => 'Файл должен иметь расширение ' . implode(',', $valid)]]);
            }

            $url = Storage::disk('public')->put('/images', $request->upload);

            return response()->json([
                'fileName' => null,
                'uploaded' => 1,
                'url'   => $url
            ]);
        }
    }
}
