<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Room;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        $rooms = DB::table('rooms')->count();
        $customer = DB::table('users')->count();
        $book_rooms = DB::table('book_rooms')->count();
        $comments = DB::table('comments')->count();
        $posts = DB::table('posts')->count();
        $slide = DB::table('slides')->count();
        return view('admin.dashboard', compact('rooms','customer','book_rooms','comments','posts','slide'));

    }
}
