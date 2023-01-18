<?php

namespace App\Http\Controllers;

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
        return response()->json([
            'data' => $news
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->user()->tokenCan('crud-news')) {
            $this->validate($request, [
                'title' => ['required', 'max:255'],
                'image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
                'body' => ['required']
            ]);

            $image_path = $request->file('image')->store('image', 'public');

            $data = News::create([
                'title' => $request->title,
                'image' => $image_path,
                'body' => $request->body
            ]);

            return response($data, Response::HTTP_CREATED);
        } else {
            $response = ['message' => 'User does not have access to post a news'];
            return response($response, 403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show(News $news)
    {
        return response()->json([
            'data' => $news
        ]);
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
        if ($request->user()->tokenCan('crud-news')) {
            $news->title = $request->title;
            $news->body = $request->body;
            $news->save();

            return response()->json([
                'data' => $news
            ]);
        } else {
            $response = ['message' => 'User does not have access to update a news'];
            return response($response, 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, News $news)
    {
        if ($request->user()->tokenCan('crud-news')) {
            $news->delete();
            return response()->json([
                'message' => 'News deleted'
            ], 200);
        } else {
            $response = ['message' => 'User does not have access to delete a news'];
            return response($response, 403);
        }
    }
}
