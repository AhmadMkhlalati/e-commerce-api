<?php

namespace App\Http\Controllers\Tag;

use App\Http\Controllers\MainController;
use App\Http\Requests\Tag\StoreTagRequest;
use App\Http\Resources\Tag\RestFullTagResource;
use App\Http\Resources\Tag\SingleTagResource;
use App\Http\Resources\Tag\TagResource;
use App\Models\Tag\Tag;
use Exception;
use Illuminate\Http\Request;

class TagController extends MainController
{

    const OBJECT_NAME = 'objects.tag';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        $tag = Tag::first();
//        $tag->setTranslation('name', 'en', 'Name in English');
//

        if ($request->method()=='POST') {
            $searchKeys=['id','name'];
          return $this->getSearchPaginated(TagResource::class, Tag::class,$request, $searchKeys);
        }
        return $this->successResponsePaginated(TagResource::class,Tag::class);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTagRequest $request)
    {
        $tag=new Tag();
        $tag->name= ($request->name);


        if(!$tag->save())
            return $this->errorResponse(
                __('messages.failed.create',['name' => __(self::OBJECT_NAME)])
            );

        return $this->successResponse(
            __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
            [
                'tag' => new SingleTagResource($tag)
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Tag $tag)
    {
         return $this->successResponse(
             'Success!',
             [
                 'tag' => new SingleTagResource($tag)
             ]
         );
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
    public function update(StoreTagRequest $request, Tag $tag)
    {
        $tag->name= ($request->name);

        if(!$tag->save())
            return $this->errorResponse(
                __('messages.failed.update',['name' => __(self::OBJECT_NAME)]),
            );

        return $this->successResponse(
            __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
            [
                'tag' => new SingleTagResource($tag)
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Tag $tag)
    {
        if(!$tag->delete())
            return $this->errorResponse(
                __('messages.failed.delete',['name' => __(self::OBJECT_NAME)])
            );

        return $this->successResponse(
            __('messages.success.delete',['name' => __(self::OBJECT_NAME)]),
            [
                'tag' => new SingleTagResource($tag)
            ]
        );
    }

    public function getTableHeaders(){
        return $this->successResponse('Success!' ,['headers' => __('headers.tags') ]);
    }

    public function getTagsData(){
        return $this->successResponsePaginated(RestFullTagResource::class,Tag::class);
    }
}

