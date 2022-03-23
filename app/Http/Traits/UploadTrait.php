<?php

namespace App\Http\Traits;

trait UploadTrait
{
    public function uploadFile($file, $folder)
    {

        if (!$file->isValid())
            return response()->json(['error_file_upload'], 400);

        $filename = time() . $file->getClientOriginalName();
        $path = public_path()  . $folder;
        $file->move($path, $filename);

        return $filename;
    }
}
