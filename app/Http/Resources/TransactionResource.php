<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'type' => $this->type,
            'amount' => $this->amount,
            'remarks' => $this->remarks,
            'balance_before' => $this->balance_before,
            'balance_after' => $this->balance_after,
            'user' => $this->user
        ];
    }
}
