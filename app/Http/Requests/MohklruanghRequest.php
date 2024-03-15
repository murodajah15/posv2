<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MohklruanghRequest extends FormRequest
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
            'nomohon' => ['required'],
            'tglmohon' => ['required'],
        ];
    }
    public function messages()
    {
        return [
            'nomohon.unique' => 'Nomor tidak boleh sama',
            'nomohon.required' => 'Nomor harus di isi',
            'tglmohon.required' => 'Tanggal harus di isi',
        ];
    }
}
