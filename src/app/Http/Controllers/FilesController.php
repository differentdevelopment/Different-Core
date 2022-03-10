<?php

namespace Different\DifferentCore\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Different\DifferentCore\app\Models\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FilesController extends Controller
{
    /**
     * @param File $file
     */
    public function __invoke(File $file)
    {
        return $this->getFile($file);
    }

    /**
     * @param File $file
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public static function getFile(File $file)
    {
        $file_path = self::getPath($file);
        return Storage::response($file_path);
    }

    /**
     * @param File $file
     * @return \Illuminate\Support\Facades\Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public static function getFileBase64(File $file)
    {
        $file_path = self::getPath($file);
        return Response::make('data:' . Storage::mimeType($file_path) . ';base64,' . base64_encode(Storage::get($file_path)), 200);
    }

    /**
     * @param File $file
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public static function getFileDownload(File $file)
    {
        $file_path = self::getPath($file);
        return Storage::download($file_path, $file->original_name);
    }

    
    /**
     * @param File $file
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public static function getFileUrl(File $file)
    {
        $file_path = self::getPath($file);
        return Storage::url($file_path);
    }

    /**
     * @param File $file
     * @param int $minutes
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public static function getFileTemporaryUrl(File $file, $minutes = 5)
    {
        $file_path = self::getPath($file);
        return Storage::temporaryUrl($file_path, now()->addMinutes($minutes));
    }

    /**
     * @param File $file
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private static function getPath(File $file)
    {
        if (!Storage::exists($file->path)) {
            return "";
        }

        return $file->path;
    }

    /**
     * @param File $file
     * @return int
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public static function getFileSize(File $file)
    {
        $file_path = self::getPath($file);
        return Storage::size($file_path);
    }

    /**
     * Stores file in storage and creates db entry
     * @param UploadedFile $file
     * @param string|null $directory
     * @return File|Builder|Model
     */
    public static function postFile(UploadedFile $file, string $directory = null): File
    {
        return self::insertUploadedFile(
            $file,
            $directory,
            $file->getClientOriginalName(),
            $file->getMimeType()
        );
    }

    /**
     * Stores base64 image in storage as file and creates db entry
     * @param string $base64
     * @param null $partner
     * @param string|null $storage_dir
     * @param string|null $disk
     * @param string|null $original_name
     * @param int|null $file_id
     * @return File
     */
    public static function postFileBase64(string $base64, string $directory = null, ?string $original_name = null): File
    {
        $image_parts = explode(";base64,", $base64);
        $image_type_aux = explode("data:", $image_parts[0]);
        $image_type_file = explode("/", $image_type_aux[1]);
        $safe_name = $original_name ?? Str::uuid()->toString() . '.' . $image_type_file[1];

        $tmp_file_path = sys_get_temp_dir() . '/' . Str::uuid()->toString();
        $file_data = base64_decode($image_parts[1]);
        file_put_contents($tmp_file_path, $file_data);
        $tmp_file = new \Illuminate\Http\File($tmp_file_path);

        if ($directory === null) {
            $directory = "";
        } else {
            if (Str::startsWith($directory, '/')) {
                Str::replaceFirst('/', '', $directory);
            }

            if (Str::endsWith($directory, '/')) {
                Str::replaceLast('/', '', $directory);
            }
        }

        $file_path = $directory . '/' . $safe_name;
        Storage::put($file_path, $file_data, 'public');

        $file = new File;
        $file->original_name = $safe_name;
        $file->mime_type = $tmp_file->getMimeType();
        $file->path = $file_path;
        $file->save();

        unlink($tmp_file_path);
        
        return $file;
    }

    /**
     * Stores the UploadedFile and creates the db entry
     * @param UploadedFile $uploaded_file
     * @param string|null $directory
     * @param string|null $original_name
     * @param string|null $mime_type
     * @return File|Builder|Model
     */
    private static function insertUploadedFile(
        UploadedFile $uploaded_file,
        string $directory = null,
        string $original_name = null,
        string $mime_type = null
    ): File
    {
        if ($directory === null) {
            $directory = "";
        } else {
            if (Str::startsWith($directory, '/')) {
                Str::replaceFirst('/', '', $directory);
            }

            if (Str::endsWith($directory, '/')) {
                Str::replaceLast('/', '', $directory);
            }
        }

        $path = Storage::putFile($directory, $uploaded_file, 'public');

        $file = new File;
        $file->original_name = $original_name;
        $file->mime_type = $mime_type;
        $file->path = $path;
        $file->save();
        
        return $file;
    }

    public static function deleteFile(File $file, bool $withModel = false) {
        $file_path = self::getPath($file);

        $delete = Storage::delete($file_path);
        if ($delete && $withModel) {
            $file->delete();
        }

        return Response::make('', 204);
    }
}
