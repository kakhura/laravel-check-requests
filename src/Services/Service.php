<?php

namespace Kakhura\CheckRequest\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Kakhura\CheckRequest\Helpers\UploadHelper;
use Kakhura\CheckRequest\Models\Base;

class Service
{
    /**
     * @param array $deletePathes
     * @return void
     */
    protected function deleteFiles(array $deletePathes)
    {
        File::delete($deletePathes);
    }

    /**
     * @param UploadedFile $file
     * @param string $uploadPath
     * @param array $deletePathes
     * @param Base $model
     * @return array
     */
    public function uploadFile(UploadedFile $file = null, string $uploadPath = null, array $deletePathes = [], Base $model = null, bool $notUploaded = false, bool $isImage = true, string $fileName = null, string $thumbFileName = null): array
    {
        if ($notUploaded) {
            return [
                'fileName' => null,
                'thumbFileName' => null,
            ];
        }
        if (!is_null($file)) {
            $file = UploadHelper::uploadFile($file, $uploadPath, $isImage);
            if (count($deletePathes) > 0) {
                $this->deleteFiles($deletePathes);
            }
        } elseif (!is_null($model)) {
            if ($isImage) {
                $file = [
                    'fileName' => !is_null($fileName) ? $model->$fileName : $model->image,
                    'thumbFileName' => !is_null($thumbFileName) ? $model->$thumbFileName : $model->thumb,
                ];
            } else {
                $file = [
                    'fileName' => $model->$fileName,
                ];
            }
        } else {
            $file = [
                'fileName' => null,
                'thumbFileName' => null,
            ];
        }
        return $file;
    }
}
