<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OpnamehRequest extends FormRequest
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
            'noopname' => ['required'],
            'tglopname' => ['required'],
        ];
    }
    public function messages()
    {
        return [
            'noopname.unique' => 'Nomor tidak boleh sama',
            'noopname.required' => 'Nomor harus di isi',
            'tglopname.required' => 'Tanggal harus di isi',
        ];
    }
}
