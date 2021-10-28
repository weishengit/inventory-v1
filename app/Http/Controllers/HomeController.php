<?php

namespace App\Http\Controllers;

use App\Models\ReleaseOrder;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function new()
    {
        return view('new');
    }

    public function incoming()
    {
        $pos = PurchaseOrder::orderBy('id', 'DESC')->get();
        return view('movement.incoming', ['pos' => $pos]);
    }

    public function outgoing()
    {
        $ros = ReleaseOrder::orderBy('id', 'DESC')->get();
        return view('movement.outgoing', ['ros' => $ros]);
    }

    public function statistics()
    {
        return view('reports.statistics');
    }
}
