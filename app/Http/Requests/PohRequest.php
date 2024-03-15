<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PohRequest extends FormRequest
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
            // 'nopo' => ['required', Rule::unique('poh')->ignore($this->poh)],
            'nopo' => ['required'],
            'tglpo' => ['required'],
            'kdsupplier' => ['required'],
        ];
    }
    public function messages()
    {
        return [
            'nopo.unique' => 'Nomor tidak boleh sama',
            'nopo.required' => 'Nomor harus di isi',
            'tglpo.required' => 'Tanggal harus di isi',
            'kdsupplier.required' => 'Supplier harus di isi',
        ];
    }
}
