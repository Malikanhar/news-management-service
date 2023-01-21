<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Jobs\CommentCreation;
use App\Models\Comment;
use App\Models\News;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->user()->cannot('create', Comment::class)) {
            return (new CommentResource('User does not have an access to post a comment', null))
                ->response()
                ->setStatusCode(Response::HTTP_FORBIDDEN);
        }

        $news = News::where('id', '=', $request->news_id)->first();
        if ($news === null) {
            return (new CommentResource('News not found! Unable to post your comment.', null))
                ->response()
                ->setStatusCode(Response::HTTP_PRECONDITION_FAILED);
        }

        $this->validate($request, [
            'news_id' => ['required'],
            'body' => ['required', 'max:255']
        ]);

        $comment = new Comment([
            'user_id' => $request->user()->id,
            'news_id' => $request->news_id,
            'body' => $request->body
        ]);

        CommentCreation::dispatch($comment->toArray());

        return (new CommentResource('Success', $comment))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
