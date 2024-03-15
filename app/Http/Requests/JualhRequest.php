<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JualhRequest extends FormRequest
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
            'nojual' => ['required'],
            'tgljual' => ['required'],
            'kdcustomer' => ['required'],
            'kdsales' => ['required'],
            'carabayar' => ['required'],
        ];
    }
    public function messages()
    {
        return [
            'nojual.unique' => 'Nomor tidak boleh sama',
            'nojual.required' => 'Nomor harus di isi',
            'tgljual.required' => 'Tanggal harus di isi',
            'kdcustomer.required' => 'Customer harus di isi',
            'kdsales.required' => 'Sales harus di isi',
            'carabayar.required' => 'Cara Bayar harus di isi',
        ];
    }
}
