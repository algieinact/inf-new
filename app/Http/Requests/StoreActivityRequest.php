<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRequest extends FormRequest
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
            'location' => 'required|string|max:255',
            'event_date' => 'required|date|after:now',
            'registration_deadline' => 'required|date|before:event_date',
            'price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
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
            'name.required' => 'Nama activity wajib diisi',
            'name.max' => 'Nama activity maksimal 255 karakter',
            'description.required' => 'Deskripsi wajib diisi',
            'location.required' => 'Lokasi wajib diisi',
            'location.max' => 'Lokasi maksimal 255 karakter',
            'event_date.required' => 'Tanggal event wajib diisi',
            'event_date.date' => 'Tanggal event harus berupa tanggal yang valid',
            'event_date.after' => 'Tanggal event harus setelah hari ini',
            'registration_deadline.required' => 'Batas registrasi wajib diisi',
            'registration_deadline.date' => 'Batas registrasi harus berupa tanggal yang valid',
            'registration_deadline.before' => 'Batas registrasi harus sebelum tanggal event',
            'price.required' => 'Harga wajib diisi',
            'price.numeric' => 'Harga harus berupa angka',
            'price.min' => 'Harga tidak boleh negatif',
            'capacity.required' => 'Kapasitas wajib diisi',
            'capacity.integer' => 'Kapasitas harus berupa angka',
            'capacity.min' => 'Kapasitas minimal 1',
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
}

