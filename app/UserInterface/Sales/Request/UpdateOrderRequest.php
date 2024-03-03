<?php

namespace App\UserInterface\Sales\Request;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
   
    public function rules(): array
    {
        return [
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer',
        ];
    }
}
