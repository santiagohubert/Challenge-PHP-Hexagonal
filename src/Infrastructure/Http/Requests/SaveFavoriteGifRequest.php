<?php

declare(strict_types=1);

namespace Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class SaveFavoriteGifRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'gif_id' => ['required', 'string'],
            'alias' => ['required', 'string', 'max:255'],
            'user_id' => [
                'required',
                'integer',
                'min:1',
                Rule::in([(int) $this->user()?->id]),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_id.in' => 'USER_ID must match the authenticated user.',
        ];
    }
}
