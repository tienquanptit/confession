<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use Pusher\Pusher;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommentRequest $request)
    {
        $user = Auth::user();

        $post = Post::findOrFail($request->post_id);
        $users = User::findOrFail($request->user_id);

        $data['post_id'] = $post->title;
        $data['user_id'] = $users->name;
        $data['body'] = $request->body;
        $data['users'] = $post->user_id;
        $data['url'] = $request->url;

        $options = [
            'cluster' => 'ap1',
            'encrypted' => true,
        ];

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        $pusher->trigger('CommentEvent', 'send-comment', $data);

        Comment::create([
            'post_id' => $request->post_id,
            'user_id' => $request->user_id,
            'body' => $data['body'],
        ]);

        $comment = Comment::where('post_id', $request->post_id)
            ->where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->first();

        return response()->json([
            'error' => false,
            'message' => 'Comment Success!',
            'data' => $request->all(),
            'info' => Auth::user(),
            'comment_id' => $comment->id,
            'created_at' => date('Y-m-d H:i:s', time()),
            'post_id' => $request->post_id,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json([
            'error' => false,
            'message' => __('message.delete_success'),
            'data' => $id,
        ]);
    }
}
