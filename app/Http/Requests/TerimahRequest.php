<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TerimahRequest extends FormRequest
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
            'noterima' => ['required'],
            'tglterima' => ['required'],
        ];
    }
    public function messages()
    {
        return [
            'noterima.unique' => 'Nomor tidak boleh sama',
            'noterima.required' => 'Nomor harus di isi',
            'tglterima.required' => 'Tanggal harus di isi',
        ];
    }
}
