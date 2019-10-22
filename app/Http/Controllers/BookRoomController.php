<?php

namespace App\Http\Controllers;

use App\Book_Room;
use App\Http\Requests\BookRoom;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Auth;
use  DB;
use \PDF;

class BookRoomController extends Controller
{
    public function addBookRoom(BookRoom $request)
    {

        if (!isset(Auth::user()->id)) {
            return redirect()->back()->withErrors(['Bạn phải đăng nhập !']);
        }
        $start_time = Carbon::parse($request->input('start_time'));
//        return $start_time;
        $finish_time = Carbon::parse($request->input('finish_time'));
        $user_id = Auth::user()->id;
        $room_id = $request->room_id;
        $hours = $start_time->diffInHours($finish_time, false);
        $price = $request->price;
        $total = $hours * $price;
        $date_start = date_format(date_create($request->input('start_time')), "Y-m-d H:i:s");

        $date_end = date_format(date_create($request->input('finish_time')), "Y-m-d H:i:s");

        echo $date_start . "<br>";
        echo $date_end;
//        dd($date_start);
        $check_book_room = DB::table('book_rooms')->where('id_room', '=', $room_id)
            ->where('time_start', '<=', $date_start)->where('time_end', '>=', $date_start)->where('status', '=', 1)
            ->count();


        if ($check_book_room > 0) {
            return redirect()->back()->withErrors(['Phòng này đã được đặt !']);

        } else {
            $book_rooms = new Book_Room();
            $book_rooms->id_user = $user_id;
            $book_rooms->id_room = $room_id;
            $book_rooms->time_start = $request->input('start_time');
            $book_rooms->time_end = $request->input('finish_time');
            $book_rooms->total = $total;

            $book_rooms->save();

            return redirect()->back()->with('success', 'Bạn đã đặt phòng thành công');
        }


    }

    public function getList()
    {
        $bookrooms = Book_Room::paginate(10);

        return view('admin.bookroom.list', compact('bookrooms'));
    }

    public function returnRoom($id)
    {
        Book_Room::where('id', $id)
            ->update(['status' => 0]);
        return redirect()->back()->with('success', 'Phòng đã được trả ');

    }

    public function delete($id)
    {
        $book_room = Book_Room::find($id);
        $book_room->delete();
        return redirect()->back()->with('success', 'Xóa thành công');

    }

    public function generatePDF($id)
    {
        $id = Book_Room::find($id);
        $data = ['title' => 'Welcome to HDTuto.com'];
        $pdf = PDF::loadView('admin.bookroom.export', $id);

        return $pdf->download('hodon.pdf');
    }

}
