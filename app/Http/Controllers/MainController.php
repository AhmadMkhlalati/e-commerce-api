<?php

namespace App\Http\Controllers;


use App\Exceptions\UnauthorizedException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\FlareClient\Http\Exceptions\NotFound;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MainController extends Controller
{
    public function __construct()
    {
    }

    protected function successResponse($message = 'Success!', array $data = [], $returnCode = 1, $statusCode = 200): \Illuminate\Http\JsonResponse
    {
        return successResponse($message, $data, $returnCode, $statusCode);
    }

    protected function errorResponse($message = 'An error occurred please try again later', array $data = [], $returnCode = -1, $statusCode = 200): \Illuminate\Http\JsonResponse
    {
        return errorResponse($message, $data, $returnCode, $statusCode);
    }

    protected function successResponsePaginated($resource, $model, array $relation = [], $pagintaion = null)
    {
        $pagination = $pagintaion ? $pagintaion : config('defaults.default_pagination');
        return ($resource::collection($model::with($relation)->paginate($pagination)));
    }

    public function imageUpload($file, $folderpath)
    {
        return uploadImage($file, $folderpath);
    }

    public function removeImage($folderpath)
    {
        return removeImage($folderpath);
    }

    public function getSearchPaginated($resource, $model, Request $request, $searchKeys, array $relations = [], array $searchRelationsKeys = [])
    {
        $data = $request->data ?? [];
        $relationKeysArr = [];
        foreach ($searchRelationsKeys as $relation => $searchRelationKeys) {
            foreach ($searchRelationKeys as $key => $dbColumn) {
                if (!isset($relationKeysArr[$key]))
                    $relationKeysArr[$key] = [];
                $relationKeysArr[$key][] = $relation;
            }
        }
        if (is_string($model)) {
            //@TODO search about it :D (call_user_func)
            $model = call_user_func($model . '::query')->with($relations);
        } else {
            $model = $model->with($relations);
        }
        $globalValue = strtolower($request->general_search);
        if (!empty(trim($globalValue))) {
            $model->when($request->has('general_search') && $request->general_search != null, function ($query) use ($searchKeys, $globalValue, $request, $searchRelationsKeys) {
                foreach ($searchKeys as $key => $attribute) {
                    $query->oRwhereRaw('lower(' . $attribute . ') like (?)', ["%$globalValue%"]);
                }

                foreach ($searchRelationsKeys as $relation => $relationKeys) {

                    foreach ($relationKeys as $dbColumn) {
                        $query->oRwhereHas($relation, fn($query) => $query->whereRaw('lower(' . $dbColumn . ') like (?)', ["%$globalValue%"]));
                    }
                }
            });
        }
        if (is_array($data) && !empty($data)) {
            $model->where(function ($query) use ($data, $searchKeys, $relationKeysArr, $searchRelationsKeys,) {
                foreach ($data as $key => $value) {
                    $value = strtolower($value);
                    if (empty(trim($value)))
                        continue;
                    if ((in_array($key, $searchKeys) && !empty($value))) {
                        $query->whereRaw('lower(' . $key . ') like (?)', ["%$value%"]);
                    } elseif (!empty($relationKeysArr[$key])) {
                        $query->where(function ($subQuery) use ($relationKeysArr, $key, $searchRelationsKeys, $value) {
                            foreach ($relationKeysArr[$key] as $key2 => $relation) {
                                $dbColumn = $searchRelationsKeys[$relation][$key];
                                if ($key2 == 0)
                                    $subQuery->whereHas($relation, fn($query) => $query->whereRaw('lower(' . $dbColumn . ') like (?)', ["%$value%"]));
                                else
                                    $subQuery->orWhereHas($relation, fn($query) => $query->whereRaw('lower(' . $dbColumn . ') like (?)', ["%$value%"]));
                            }
                        });
                    }
                }
            });
        }

        $rows = $model->paginate($request->limit ?? config('defaults.default_pagination'));

        return $resource::collection($rows);

    }

}


