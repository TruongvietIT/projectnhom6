<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use Auth;
use DB;

class CommentController extends Controller
{
    public function addComment(Request $request, $id)
    {
        $comment = new Comment();
        $comment->content = $request->contents;

        $comment->id_user = Auth::user()->id;
        $comment->id_room = $id;
        $comment->save();

        return redirect()->back();


    }

    public function listCommentRooms()
    {
        $comments = DB::table('comments')
            ->select('comments.id as comment_id','comments.id_user','comments.id_room', 'comments.content','comments.created_at','users.id', 'users.name as user_name', 'rooms.id', 'rooms.name')
            ->join('users', 'comments.id_user', '=', 'users.id')
            ->join('rooms', 'comments.id_room', '=', 'rooms.id')
            ->paginate(9);

        return view('admin.comment.index', compact('comments'));
    }

    public function delete($id)
    {

        $comment = DB::table('comments')->where('id', $id)->delete();

        return redirect()->route('comments.room_list')->with('success', 'success');
    }
}
