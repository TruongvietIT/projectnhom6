<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddPostRequest;
use App\Post;
use Illuminate\Http\Request;
use DB;

class PostController extends Controller
{
    public function index()
    {
        $posts = DB::table('posts')->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    public function getAdd()
    {
        return view('admin.posts.add');
    }

    public function postAdd(AddPostRequest $request)
    {
        $post = new Post();
        $post->title = $request->name;
        $post->content = $request->contents;
        $file = $request->images;
        $file_name = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path().'/uploads/posts/', $file_name);
        $post->image = $file_name;
        $post->save();
        return redirect()->route('post.list')->with('success', 'success');

    }

    public function getEdit($id)
    {
        $post = Post::find($id);
        return view('admin.posts.edit', compact('post'));

    }

    public function postEdit(Request $request, $id)
    {
        $post = Post::find($id);
        $post->title = $request->name;
        $post->content = $request->contents;

        if ($request->hasFile('images')) {
            $file = $request->file('images');
            $file_name = time() . '.' . $file->getClientOriginalExtension();
//            $file->move('uploads/rooms', $file_name);
            $file->move(public_path().'/uploads/posts/', $file_name);
            $path = public_path()."/uploads/posts/".$post->image;
            unlink($path);
            $post->image = $file_name;
        }
        $post->save();
        return redirect()->route('post.list')->with('success', 'success');
    }

    public function delete($id)
    {
        $post = Post::find($id);
        $path = public_path()."/uploads/posts/".$post->image;
        unlink($path);
        $post->delete();
        return redirect()->route('post.list')->with('success', 'success');
    }
}
