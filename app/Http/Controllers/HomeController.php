<?php

namespace App\Http\Controllers;

use App\Models\Item;
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
        $totalStocks = Item::sum('quantity');
        $pendingPO = PurchaseOrder::where('status_id', '!=', 4)->Where('status_id', '!=', 5)->count();
        $pendingRO = ReleaseOrder::where('status_id', '!=', 4)->Where('status_id', '!=', 5)->count();
        $criticalLevel = Item::whereColumn('quantity', '<=', 'critical_level')->count();

        return view('home', [
            'totalStocks' => $totalStocks,
            'pendingPO' => $pendingPO,
            'pendingRO' => $pendingRO,
            'criticalLevel' => $criticalLevel,
        ]);
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
