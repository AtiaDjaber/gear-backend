<?php

namespace App\Http\Controllers;

use App\Article;
use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::all();
        return response()->json(['message' => 'Success', 'data' => $articles], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validateArticle();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }
        if (Article::create($validator->validate())) {
            return response()->json(['message' => 'Article Created', 'data' => $validator->validate()], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return response()->json(['message' => 'Success', 'data' => $article], 200);
    }

    public function show_comments(Article $article)
    {
        $comments = $article->comments;
        if (count($comments) > 0) {
            return response()->json(['message' => 'Success', 'data' => $comments], 200);
        }
        return response()->json(['message' => 'No Comment Found', 'data' => null], 200);
    }

    public function show_best_comment(Article $article)
    {
        $comment = Comment::find($article->best_comment_id);
        return response()->json(['message' => 'Success', 'data' => $comment], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        if ($article->delete()) {
            return response()->json(['message' => 'Article Deleted', 'data' => null], 200);
        }
        return response()->json(['message' => 'Error Occured', 'data' => null], 400);
    }

    public function validateArticle()
    {
        return Validator::make(request()->all(), [
            'title' => 'required|string|min:3|max:255',
            'slug' => 'required|string|min:3|max:25',
            'body' => 'required|string|min:5|max:255',
        ]);
    }
}
