<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Meta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'header_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'paper_size' => 'required|in:A4,A5',
            'note' => 'required',
        ], [
            'header_image.image' => 'File harus berupa gambar',
            'header_image.mimes' => 'File harus berupa gambar dengan format: jpeg, png, jpg, gif, svg',
            'header_image.max' => 'Ukuran file tidak boleh lebih dari 2MB',
            'paper_size.required' => 'Ukuran kertas wajib diisi',
            'paper_size.in' => 'Ukuran kertas harus A4 atau A5',
            'note.required' => 'Catatan wajib diisi',
        ]);

        switch ($request->type) {
            case 'service_invoice':
                $this->update($request, 'service');
                break;

            case 'order_invoice':
                $this->update($request, 'order');
                break;

            case 'sale_invoice':
                $this->update($request, 'sale');
                break;

            default:
                break;
        }

        return back();
    }

    public function update(Request $request, $type): void
    {
        if ($request->hasFile('header_image')) {
            $oldPath = Meta::get('invoice_' . $type . '_header_image');
            if ($oldPath) {
                Storage::delete("public/" . $oldPath);
            }

            $request->file('header_image')->store('public/meta');
            Meta::set('invoice_' . $type . '_header_image', $request->file('header_image')->hashName('meta'));
        }

        Meta::set('invoice_' . $type . '_paper_size', $request->paper_size);
        Meta::set('invoice_' . $type . '_note', $request->note);
    }
}
