<?php


namespace App\Services;


class FileService
{
    public function upload($file, $path = '/images/content', $arrayKey = null)
    {
        return $this->move($file, $path);
    }

    public function multipleUpload($file, $path = '/images/content')
    {
        $fileNames = [];

        try {
            if (gettype($file) == "array") {
                foreach ($file as $item) {
                    $fileNames[] = $this->move($item, $path);
                }
            }

        } catch (\Exception $exception) {
            $fileNames = $exception->getMessage();
        }

        return $fileNames;
    }

    public function move($file, $path)
    {
        if ($file) {
            $fileName = $path . '/' . md5(uniqid()) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path("$path"), $fileName);
            return $fileName;
        }
    }

    public function remove($fileName)
    {
        $file = public_path('') . $fileName;
        if (file_exists($file)) {
            unlink($file);
            return true;
        }
        return null;
    }
}
