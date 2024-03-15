<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Approv_batas_piutangRequest extends FormRequest
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
            'noapprov' => ['required'],
            'tglapprov' => ['required'],
            'nojual' => ['required'],
        ];
    }
    public function messages()
    {
        return [
            'noapprov.unique' => 'Nomor tidak boleh sama',
            'noapprov.required' => 'Nomor harus di isi',
            'tglapprov.required' => 'Tanggal harus di isi',
            'nojual.required' => 'Dokumen harus di isi',
        ];
    }
}
