<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TbmoveRequest extends FormRequest
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
      'kode' => ['required', Rule::unique('tbmove')->ignore($this->tbmove)],
      'nama' => ['required'],
    ];
  }
  public function messages()
  {
    return [
      'kode.unique' => 'Kode tidak boleh sama',
      'kode.required' => 'Kode harus di isi',
      'nama.required' => 'Nama harus di isi',
    ];
  }
}
