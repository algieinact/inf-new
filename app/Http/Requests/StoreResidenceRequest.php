<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResidenceRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->hasRole('provider') || auth()->user()->hasRole('admin');
    }

    public function rules()
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'rental_period' => 'required|in:monthly,yearly',
            'price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'facilities' => 'required|array|min:1',
            'facilities.*' => 'string|max:255',
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'discount_type' => 'nullable|in:percentage,flat',
            'discount_value' => 'nullable|numeric|min:0',
            'is_active' => 'boolean'
        ];
    }

    public function messages()
    {
        return [
            'category_id.required' => 'Kategori wajib dipilih',
            'category_id.exists' => 'Kategori tidak valid',
            'name.required' => 'Nama residence wajib diisi',
            'name.max' => 'Nama residence maksimal 255 karakter',
            'description.required' => 'Deskripsi wajib diisi',
            'address.required' => 'Alamat wajib diisi',
            'rental_period.required' => 'Periode sewa wajib dipilih',
            'rental_period.in' => 'Periode sewa harus monthly atau yearly',
            'price.required' => 'Harga wajib diisi',
            'price.numeric' => 'Harga harus berupa angka',
            'price.min' => 'Harga tidak boleh negatif',
            'capacity.required' => 'Kapasitas wajib diisi',
            'capacity.integer' => 'Kapasitas harus berupa angka',
            'capacity.min' => 'Kapasitas minimal 1',
            'facilities.required' => 'Fasilitas wajib diisi',
            'facilities.array' => 'Fasilitas harus berupa array',
            'facilities.min' => 'Minimal 1 fasilitas',
            'images.required' => 'Gambar wajib diupload',
            'images.array' => 'Gambar harus berupa array',
            'images.min' => 'Minimal 1 gambar',
            'images.max' => 'Maksimal 10 gambar',
            'images.*.image' => 'File harus berupa gambar',
            'images.*.mimes' => 'Format gambar: jpeg, png, jpg, gif',
            'images.*.max' => 'Ukuran gambar maksimal 2MB',
            'discount_type.in' => 'Tipe diskon harus percentage atau flat',
            'discount_value.numeric' => 'Nilai diskon harus berupa angka',
            'discount_value.min' => 'Nilai diskon tidak boleh negatif'
        ];
    }

    protected function prepareForValidation()
    {
        // Ensure discount fields are properly set
        if ($this->discount_type && !$this->discount_value) {
            $this->merge(['discount_value' => 0]);
        }

        if (!$this->discount_type) {
            $this->merge(['discount_value' => null]);
        }
    }
}

