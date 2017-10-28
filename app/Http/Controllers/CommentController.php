<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Film;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * CommentController constructor.
     */
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $film = Film::find($request->film_id);
        if (!$film) {
            abort(404);
        }

        $comment = new Comment();
        $comment->name = $request->name;
        $comment->comment = $request->description;

        $film->comments()->save($comment);

        return redirect('/#/films/' . $request->slug);

    }
}
