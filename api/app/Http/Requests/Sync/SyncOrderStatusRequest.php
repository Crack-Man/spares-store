<?php

namespace App\Http\Requests\Sync;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SyncOrderStatusRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => 'required|string|in:Новый,Подтвержден,В обработке,Отправлен,Завершен,Отменен',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
