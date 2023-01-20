<?php

namespace App\Http\Controllers;

use App\Events\NewsEvent;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class NewsController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = News::paginate(10);
        return (new NewsResource('Success', $news))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->user()->cannot('create', News::class)) {
            return (new NewsResource('User does not have an access to create a news', null))
                ->response()
                ->setStatusCode(Response::HTTP_FORBIDDEN);
        }

        $this->validate($request, [
            'title' => ['required', 'max:255'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'body' => ['required']
        ]);

        $image_path = $request->file('image')->store('image', 'public');

        $news = News::create([
            'title' => $request->title,
            'image' => $image_path,
            'body' => $request->body
        ]);

        event(new NewsEvent($news, 'create'));

        return (new NewsResource('Success', $news))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show(News $news)
    {
        return (new NewsResource('Success', $news->load('comments')))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, News $news)
    {
        if ($request->user()->cannot('update', $news)) {
            return (new NewsResource('User does not have an access to update a news', null))
                ->response()
                ->setStatusCode(Response::HTTP_FORBIDDEN);
        }

        $news->title = $request->title;
        $news->body = $request->body;
        $news->save();

        event(new NewsEvent($news, 'update'));

        return (new NewsResource('Success', $news))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, News $news)
    {
        if ($request->user()->cannot('delete', $news)) {
            return (new NewsResource('User does not have an access to delete a news', null))
                ->response()
                ->setStatusCode(Response::HTTP_FORBIDDEN);
        }

        Storage::delete('public/' . $news->image);

        $news->delete();

        event(new NewsEvent($news, 'delete'));

        return (new NewsResource('Success', null))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
