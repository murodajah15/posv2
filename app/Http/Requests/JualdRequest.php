<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JualdRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 'noso' => ['required', Rule::unique('soh')->ignore($this->soh)],
            'kdbarang' => ['required'],
        ];
    }
    public function messages()
    {
        return [
            'kdbarang.required' => 'Barang harus di isi',
        ];
    }
}
