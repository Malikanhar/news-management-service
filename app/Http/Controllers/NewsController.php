<?php

namespace App\Http\Controllers;

use App\Events\NewsEvent;
use App\Models\News;
use Illuminate\Http\Request;
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
        $response = [
            'message' => 'Success',
            'data' => $news
        ];
        return response($response, Response::HTTP_OK);
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
            $response = [
                'message' => 'User does not have an access to create a news'
            ];
            return response($response, Response::HTTP_FORBIDDEN);
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

        $response = [
            'message' => 'Success',
            'data' => $news
        ];
        return response($response, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show(News $news)
    {
        $response = [
            'message' => 'Success',
            'data' => $news
        ];
        return response($response, Response::HTTP_OK);
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
            $response = [
                'message' => 'User does not have an access to update a news'
            ];
            return response($response, Response::HTTP_FORBIDDEN);
        }

        $news->title = $request->title;
        $news->body = $request->body;
        $news->save();

        event(new NewsEvent($news, 'update'));

        $response = [
            'message' => 'Success',
            'data' => $news
        ];
        return response($response, Response::HTTP_OK);
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
            $response = [
                'message' => 'User does not have an access to delete a news'
            ];
            return response($response, Response::HTTP_FORBIDDEN);
        }

        $news->delete();

        event(new NewsEvent($news, 'delete'));

        $response = [
            'message' => 'Success'
        ];
        return response($response, Response::HTTP_OK);
    }
}
