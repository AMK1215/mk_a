<?php

namespace App\Http\Controllers;

use App\Models\Admin\Product;
use App\Models\Webhook\Result;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    private $carbon;

    public function __construct(Carbon $carbon)
    {
        $this->carbon = $carbon;
    }

    public function index(Request $request)
    {
        $adminId = auth()->id();

        $results = $this->buildQuery($request, $adminId)->get();

        return view('report.index', compact('results'));
    }

    public function detail(Request $request, $playerId)
    {
        $details = $this->getPlayerDetails($playerId, $request->product_type_id);

        $productTypes = Product::where('is_active', 1)->get();

        return view('report.detail', compact('details', 'productTypes', 'playerId'));
    }

    private function buildQuery(Request $request, $adminId)
    {
        $query = Result::select(
            DB::raw('SUM(results.total_bet_amount) as total_bet_amount'),
            DB::raw('SUM(results.win_amount) as total_win_amount'),
            DB::raw('SUM(results.net_win) as total_net_win'),
            DB::raw('MAX(wallets.balance) as balance'),
            DB::raw('IFNULL(deposit_requests.total_amount, 0) as deposit_amount'),
            DB::raw('IFNULL(with_draw_requests.total_amount, 0) as withdraw_amount'),
            DB::raw('IFNULL(bonuses.total_amount, 0) as bonus_amount'),
            'players.name as player_name',
            'players.user_name as user_name',
            'agents.name as agent_name',
            'players.id as user_id'
        )
            ->join('users as players', 'results.user_id', '=', 'players.id')
            ->join('users as agents', 'players.agent_id', '=', 'agents.id')
            ->join('wallets', 'wallets.holder_id', '=', 'players.id')
            ->leftJoin($this->getSubquery('bonuses'), 'bonuses.user_id', '=', 'results.user_id')
            ->leftJoin($this->getSubquery('deposit_requests', 'status = 1'), 'deposit_requests.user_id', '=', 'results.user_id')
            ->leftJoin($this->getSubquery('with_draw_requests', 'status = 1'), 'with_draw_requests.user_id', '=', 'results.user_id')
            ->when($request->player_id, fn($query) => $query->where('results.player_id', $request->player_id));

        $this->applyDateFilter($query, $request);
        $this->applyRoleFilter($query, $adminId);

        return $query->groupBy('players.name', 'agents.name', 'players.id', 'players.user_name');
    }

    private function applyDateFilter($query, Request $request)
    {
        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('results.created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59',
            ]);
        } else {
            $query->whereBetween('results.created_at', [
                $this->carbon->startOfMonth()->toDateTimeString(),
                $this->carbon->endOfMonth()->toDateTimeString(),
            ]);
        }
    }

    private function applyRoleFilter($query, $adminId)
    {
        if (Auth::user()->hasRole('Master')) {
            $query->where('agents.agent_id', $adminId);
        } elseif (Auth::user()->hasRole('Agent')) {
            $query->where('agents.id', $adminId);
        }
    }

    private function getPlayerDetails($playerId, $productType)
    {
        return Result::where('user_id', $playerId)->when($productType, function ($query) use ($productType) {
            $query->where('game_provide_name', $productType);
        })->orderBy('id', 'desc')->get();
    }

    private function getSubquery($table, $condition = '1=1')
    {
        return DB::raw("(SELECT user_id, SUM(amount) AS total_amount FROM $table WHERE $condition GROUP BY user_id) AS $table");
    }
}
