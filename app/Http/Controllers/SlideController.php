<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddSlideRequest;
use App\Slide;
use Illuminate\Http\Request;

class SlideController extends Controller
{
    public function index()
    {
        $slides = Slide::all();
        return view('admin.slide.index', compact('slides'));
    }

    public function getAdd()
    {
        return view('admin.slide.add');
    }

    public function postAdd(AddSlideRequest $request)
    {
        $slide = new Slide();
        $slide->title = $request->title;
        $file = $request->images;
        $file_name = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path().'/uploads/slides/', $file_name);
        $slide->links = $file_name;
        $slide->save();
        return redirect()->route('slides.list')->with('success', 'success');

    }

    public function getEdit($id)
    {
        $slide = Slide::find($id);
        return view('admin.slide.edit', compact('slide'));
    }

    public function postEdit(Request $request, $id)
    {
        $slide = Slide::find($id);
        $slide->title = $request->title;
        if ($request->hasFile('images')) {
            $file = $request->file('images');
            $file_name = time() . '.' . $file->getClientOriginalExtension();
//            $file->move('uploads/rooms', $file_name);
            $file->move(public_path().'/uploads/slides/', $file_name);
            $path = public_path()."/uploads/slides/".$slide->links;
            unlink($path);
            $slide->links = $file_name;
        }
        $slide->save();
        return redirect()->route('slides.list')->with('success', 'success');
    }

    public function delete($id)
    {
        $slide = Slide::find($id);
        $path = public_path()."/uploads/slides/".$slide->links;
        unlink($path);
        $slide->delete();
        return redirect()->route('slides.list')->with('success', 'success');
    }
}
