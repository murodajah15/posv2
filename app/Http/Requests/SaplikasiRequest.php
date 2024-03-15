<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaplikasiRequest extends FormRequest
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
      'kd_perusahaan' => ['required', Rule::unique('saplikasi')->ignore($this->saplikasi)],
      'nm_perusahaan' => ['required']
    ];
  }
  public function messages()
  {
    return [
      'kd_perusahaan.required' => 'Kode Perusahaan harus di isi',
      'kd_perusahaan.unique' => 'Kode Perusahaan sudah terpakai',
      'nm_perusahaan.required' => 'Nama Perusahaan harus di isi'
    ];
  }
}
