<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Room;
use App\Http\Requests\AddRoom;
use DB;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = DB::table('rooms')->paginate(10);
        return view('admin.rooms.index', compact('rooms'));
    }

    public function getAdd()
    {
        return view('admin.rooms.add');
    }

    public function postAdd(AddRoom $request)
    {
        $room = new Room();
        $room->name = $request->name;
        $room->location = $request->location;
        $room->acreage = $request->acreage;
        $room->details = $request->details;
        $room->price = $request->price;
        $file = $request->images;
        $file_name = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path().'/uploads/rooms/', $file_name);
        $room->image = $file_name;
        $room->save();
        return redirect()->route('rooms.list')->with('success', 'success');
    }

    public function getEdit($id)
    {
        $room = Room::find($id);
        return view('admin.rooms.edit', compact('room'));
    }

    public function postEdit(Request $request, $id)
    {
        $room = Room::find($id);
        $room->name = $request->name;
        $room->location = $request->location;
        $room->acreage = $request->acreage;
        $room->details = $request->details;
        $room->price = $request->price;
        $file = $request->images;
        if ($request->hasFile('images')) {
            $file = $request->file('images');
            $file_name = time() . '.' . $file->getClientOriginalExtension();
//            $file->move('uploads/rooms', $file_name);
            $file->move(public_path().'/uploads/rooms/', $file_name);
            $path = public_path()."/uploads/rooms/".$room->image;
            unlink($path);
            $room->image = $file_name;
        }
        $room->save();
        return redirect()->route('rooms.list')->with('success', 'success');
    }

    public function delete($id)
    {
        $room = Room::find($id);
        $path = public_path()."/uploads/rooms/".$room->image;
        unlink($path);
        $room->delete();
        return redirect()->route('rooms.list')->with('success', 'success');

    }
}
