<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'surname' => $this->surname,
            'name' => $this->name,
            'fiscal_code' => $this->fiscal_code,
            'phone_number' => $this->phone_number,
            'role' => $this->role,
            'email' => $this->email,
            'date_of_birth' => $this->date_of_birth,
//            'updated_at' => $this->updated_at->format('d/m/Y'),
//            'detail' => $this->detail,
        ];
    }
}
