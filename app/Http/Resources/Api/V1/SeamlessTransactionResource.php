<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeamlessTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'product' => $this->product_name,
            'total_count' => $this->total_count,
            'total_bet_amount' => number_format($this->total_bet_amount, 2),
            'total_win_amount' => number_format($this->total_net_win_amount, 2)
        ];
    }
}
