<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Storage;

trait FileUploadTrait
{
    public function ImageUpload($image, $path)
    {
        $file_name = strtolower($image->getClientOriginalName());
        $file_name = explode('.', $file_name)[0];
        $file_name = str_replace(' ', '-', $file_name);
        $file_ext = strtolower($image->getClientOriginalExtension());
        $fullFileName = $file_name . "-" . time() . '.' . $file_ext;
        $image_url = $path . $fullFileName;
        $success = $image->move($path, $fullFileName);
        if ($success) {
            return $image_url;
        } else {
            return '';
        }
    }

    public function DocUpload($file, $path)
    {
        $file_name = strtolower($file->getClientOriginalName());
        $file_name = explode('.', $file_name)[0];
        $file_name = str_replace(' ', '-', $file_name);
        $uniqueFileName = uniqid() . $file_name . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($path),  $uniqueFileName);
        $file_url = $path . $uniqueFileName;
        if ($file_url) {
            return $file_url;
        } else {
            return '';
        }
    }
}
