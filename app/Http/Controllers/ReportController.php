<?php

namespace App\Http\Controllers;

use App\Book_Room;
use App\Charts\ReportChart;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $chart = new ReportChart();
        $now = Carbon::now();
        $month = $now->month;

        $book_room = DB::table("book_rooms")
        ->select('rooms.*','book_rooms.*',DB::raw('SUM(book_rooms.total) as total'))

        ->join('rooms','book_rooms.id_room', '=', 'rooms.id')
            ->groupBy("book_rooms.id_room")
            ->whereMonth('book_rooms.created_at', '=', $month)
            ->orderBy('total','DESC')
         ->get();
        $labels = $book_room->pluck('name');
        $values = $book_room->pluck('total');


        $chart = new ReportChart();
        $chart->labels($labels);
        $chart->dataset('', 'bar', $values);

        return view('admin.report.index', compact('chart'));
    }
}
