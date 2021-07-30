<?php

namespace App\Http\Controllers;

use App\Article;
use App\Comment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    public function sendNotification($token, $title, $body)
    {
        $SERVER_API_KEY =
            "AAAA9uZfwq0:APA91bHwqL0Jmv8V8X0oEhxPHkPde25jlgkWnvlwM0owdBNG_01vGl5u-PTB1jiP8fRTLxWEyfErlZHXkEFPi8E0t-CxMy5JHDTzUg4tWZtxHskNGB7hBCl3dtidk3QD7PHDACNN9xTJ";


        $data = [

            "registration_ids" => [
                $token
            ],

            "notification" => [
                "title" => $title,
                "body" => $body,
                "sound" => "default" // required for sound on ios

            ],

        ];

        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
    }

    public function index()
    {
        $comments = Comment::paginate(3);

        foreach ($comments as $comment) {
            $this->sendNotification($comment["text"], "", "");
            // $comments->push($comment["text"]) ;
        }
        return response()->json(['message' => 'Success', 'data' => $comments], 200);
    }

    public function store(Article $article)
    {

        $validator = $this->validateComment();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }

        $comment = new Comment($validator->validate());
        if ($article->comments()->save($comment)) {
            return response()->json(['message' => 'Comment Saved', 'data' => $comment], 200);
        }

        return response()->json(['message' => 'Error Occured', 'data' => null], 400);
    }

    public function best_comment(Comment $comment)
    {
        if ($comment->article->set_best_comment($comment)) {
            return response()->json(['message' => 'Best Comment Saved', 'data' => $comment], 200);
        }
        return response()->json(['message' => 'Error Occurred', 'data' => null], 400);
    }


    public function show(Comment $comment)
    {
        return response()->json(['message' => 'Success', 'data' => $comment], 200);
    }

    public function destroy(Comment $comment)
    {
        if ($comment->delete()) {
            return response()->json(['message' => 'Comment Deleted', 'data' => null], 200);
        }
        return response()->json(['message' => 'Error Occurred', 'data' => null], 400);
    }

    public function validateComment()
    {
        return Validator::make(request()->all(), [
            'text' => 'required|string|min:3|max:255',
            'star' => 'required|integer|min:0|max:5',
        ]);
    }
}
