<?php

namespace App;

class SaveImageHelperClass
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function saveBase64Image($base64Image){
    preg_match("/data:image\/(.*?);base64,/", $base64Image, $imageExtension);
    $base64Image = preg_replace("/data:image\/(.*?);base64,/", '', $base64Image);
    $base64Image = str_replace(' ', '+', $base64Image);

    $fileName = time() . '-' . rand() . '.' . $imageExtension[1];

    $imageData = base64_decode($base64Image);
    $avatarsDirectory = public_path('images');
    if (!file_exists($avatarsDirectory)) {
        mkdir($avatarsDirectory, 0755, true);
    }

    $filePath = $avatarsDirectory . '/' . $fileName;
    file_put_contents($filePath, $imageData);

    return $fileName;
    }
}
