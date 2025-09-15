<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'booking_code' => $this->booking_code,
            'check_in_date' => $this->check_in_date,
            'check_out_date' => $this->check_out_date,
            'status' => $this->status,
            'rejection_reason' => $this->rejection_reason,
            'notes' => $this->notes,
            'documents' => json_decode($this->documents, true),
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'phone' => $this->user->phone
            ],
            'bookable' => $this->getBookableResource(),
            'transaction' => $this->when($this->transaction, new TransactionResource($this->transaction)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }

    private function getBookableResource()
    {
        if ($this->bookable_type === 'App\\Models\\Residence') {
            return new ResidenceResource($this->bookable);
        } else {
            return new ActivityResource($this->bookable);
        }
    }
}

