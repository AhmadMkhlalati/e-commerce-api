<?php

namespace App\Http\Controllers\Label;

use App\Exceptions\FileErrorException;
use App\Http\Resources\Label\LabelsResource;
use App\Http\Resources\Label\SingleLableResource;
use App\Models\Label\Label;
use App\Http\Controllers\MainController;
use App\Http\Requests\Labels\StoreLabelRequest;
use App\Http\Resources\Label\RestFullLabelResource;
use Exception;
use Illuminate\Http\Request;

class LabelController extends MainController
{
    const OBJECT_NAME = 'objects.label';
    private $imagesPath = "";
    public function __construct()
    {
        $this->imagesPath = Label::$imagesPath;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->method() == 'POST') {
            $searchKeys = ['id','title', 'entity', 'color', 'image',];
            return $this->getSearchPaginated(LabelsResource::class, Label::class, $request, $searchKeys);
        }
        return $this->successResponsePaginated(LabelsResource::class, Label::class);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreLabelRequest $request)
    {
        $label = new Label();

        $dataTranslatable = (array)json_decode($request->title);
        $label->title =  ($dataTranslatable);
        $label->entity = $request->entity;
        $label->color = $request->color;

        if ($request->image) {
            $label->image = $this->imageUpload($request->file('image'), $this->imagesPath['images']);
        }
        $label->key = $request->key;

        if (!$label->save())
            return $this->errorResponse(
                __('messages.failed.create', ['name' => __(self::OBJECT_NAME)])
            );

        return $this->successResponse(
            __('messages.success.create', ['name' => __(self::OBJECT_NAME)]),
            [
                'label' => new SingleLableResource($label)
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Label $label)
    {
        return $this->successResponse('Success!', ['label' => new SingleLableResource($label)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreLabelRequest $request, Label $label)
    {

        $dataTranslatable = (array)json_decode($request->title);
        $label->title =  ($dataTranslatable);
        $label->entity = $request->entity;
        $label->color = $request->color;
        $label->key = $request->key;

        if ($request->image) {
            if (!$this->removeImage($label->image)) {
                throw new FileErrorException();
            }
            $label->image = $this->imageUpload($request->file('image'), $this->imagesPath['images']);
        }

        if (!$label->save())
            return $this->errorResponse(
                __('messages.failed.update', ['name' => __(self::OBJECT_NAME)])

            );

        return $this->successResponse(
            __('messages.success.update', ['name' => __(self::OBJECT_NAME)]),
            [
                'label' => new SingleLableResource($label)
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Label $label
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Label $label)
    {
        if (!$label->delete())
            return $this->errorResponse(
                __('messages.failed.delete', ['name' => __(self::OBJECT_NAME)])
            );

        return $this->successResponse(
            __('messages.success.delete', ['name' => __(self::OBJECT_NAME)]),
            [
                'label' => new SingleLableResource($label)
            ]
        );
    }
    public function getTableHeaders()
    {
        return $this->successResponse('Success!', ['headers' => __('headers.labels')]);
    }

    public function getLabelsData()
    {
        return $this->successResponsePaginated(RestFullLabelResource::class, Label::class);
    }
}
