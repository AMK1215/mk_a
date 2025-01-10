<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TransactionName;
use App\Http\Controllers\Controller;
use App\Models\Bonus;
use App\Models\BonusType;
use App\Models\User;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BonusController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $agentIds = [$user->id];

        if ($user->hasRole('Master')) {
            $agentIds = User::where('agent_id', $user->id)->pluck('id')->toArray();
        }

        $bonuses = Bonus::whereIn('agent_id', $agentIds)->get();

        return view('admin.bonus.index', compact('bonuses'));
    }

    public function create(Request $request)
    {
        $types = BonusType::all();

        return view('admin.bonus.create', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => ['required'],
            'remark' => ['nullable', 'string'],
            'type_id' => ['required'],
        ]);
        $agent = Auth::user();
        $player = User::find($request->id);

        if ($agent->hasRole('Master')) {
            $agent = User::where('id', $player->agent_id)->first();
        }

        if ($agent->balanceFloat < $request->amount) {
            return redirect()->back()->with('error', 'You do not have enough balance to transfer!');
        }

        $bonus = Bonus::create([
            'user_id' => $request->id,
            'type_id' => $request->type_id,
            'amount' => $request->amount,
            'before_amount' => $player->balanceFloat,
            'agent_id' => $player->agent_id,
            'created_id' => Auth::id(),
        ]);
        app(WalletService::class)->transfer($agent, $player, $request->amount, TransactionName::BonusLocal, ['agent_id' => Auth::id()]);
        $bonus->update([
            'after_amount' => $player->balanceFloat,
        ]);

        return redirect()->back()->with('success', 'Bonus Added!');
    }

    public function show($id) {}

    public function edit($id) {}

    public function update(Request $request, $id) {}

    public function destroy($id) {}

    public function search(Request $request)
    {
        $agent = Auth::user();
        $player = User::where('user_name', $request->user_name)->first();

        if (! $player) {
            return $this->response(false, 'Player not found');
        }

        if (! $this->isPlayerUnderAgent($player, $agent)) {
            return $this->response(false, 'This player is not your player');
        }

        $playerData = $player->only(['name', 'user_name', 'phone', 'id']);

        return $this->response(true, null, $playerData);
    }

    private function isPlayerUnderAgent(User $player, User $agent): bool
    {
        if ($agent->hasRole('Master')) {
            return $player->parent->agent_id === $agent->id;
        }

        return $player->agent_id === $agent->id;
    }

    private function response(bool $success, ?string $message = null, ?array $data = null)
    {
        $response = ['success' => $success];

        if ($message) {
            $response['message'] = $message;
        }

        if ($data) {
            $response['data'] = $data;
        }

        return response()->json($response);
    }
}
