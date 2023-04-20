<?php

use App\Exceptions\FileErrorException;
use App\Models\Settings\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

function uploadImage($file, $folderPath): bool|string
{
    try {
        $fileName = uniqid() . '_' . $file->getClientOriginalName();
        $path = Storage::putFileAs('public/' . $folderPath, $file, $fileName);
    } catch (FileErrorException $exception) {
        throw $exception;
    } catch (ValueError $exception) {
        throw $exception;
    } catch (ErrorException $exception) {
        throw new FileErrorException();
    }

    return $realPath = $folderPath . '/' . $fileName;
}

function getAssetsLink($path): string
{
    try {
        return asset($path) . '?v=' . File::lastModified(public_path($path));
    } catch (Exception $ex) {
        return asset($path);
    }
}

function errorResponse($message = 'An error occurred please try again later', array $data = [], $returnCode = -1, $statusCode = 500): JsonResponse
{
    $return['message'] = $message;
    $return['data'] = $data;
    $return['code'] = $returnCode;

    return response()->json($return, $statusCode);
}

function successResponse($message = 'Success!', array $data = [], $returnCode = 1, $statusCode = 200): JsonResponse
{
    $return['message'] = $message;
    $return['data'] = $data;
    $return['code'] = $returnCode;

    return response()->json($return, $statusCode);
}

function removeImage(string|null $folderPath): bool
{
    if ((empty($folderPath))) {
        return true;
    }
    if (Storage::exists($folderPath)) {
        return Storage::delete($folderPath);
    }

    return true;
}

function getSettings(array|string $key = null): mixed
{

    $settings = Cache::get(Setting::$cacheKey, fn() => Setting::all());

    if (is_array($key)) {

        $multiSettings = $settings->whereIn('title', $key);
        if ($multiSettings->count() != count($key)) {
            throw new Exception('One of the keys is not valid settings');
        }
        return $multiSettings;
    }

    if (is_string($key)) {
        $singleSettings = $settings->where('title', $key)->first();
        if (is_null($singleSettings)) {
            throw new Exception('The ' . $key . ' is not a valid settings');
        }
        return $singleSettings;

    }
    return $settings;
}

function array_to_obj($array, $obj)
{
    foreach ($array as $key => $value) {
        $id = $value->price_id;
        if (is_array($value)) {
            $obj->{$id} = new stdClass();
            array_to_obj($value, $obj->$key);
        } else {
            $obj->{$id} = $value;
        }

    }
    return $obj;
}

function arrayToObject($array)
{
    $object = new stdClass();
    return array_to_obj($array, $object);

}

function mbBaseName($filePath): bool|string
{
    $array = explode('\\', $filePath);
    return end($array);
}
