<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BelihRequest extends FormRequest
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
            'nobeli' => ['required'],
            'tglbeli' => ['required'],
            'kdsupplier' => ['required'],
        ];
    }
    public function messages()
    {
        return [
            'nobeli.unique' => 'Nomor tidak boleh sama',
            'nobeli.required' => 'Nomor harus di isi',
            'tglbeli.required' => 'Tanggal harus di isi',
            'kdsupplier.required' => 'Supplier harus di isi',
        ];
    }
}
