<?php

namespace App\Http\Controllers;

use App\Models\Meta;

class SettingController extends Controller
{
   public function index()
   {
      $metaKeys = [
         'invoice_service_header_image', 'invoice_service_paper_size', 'invoice_service_note',
         'invoice_order_header_image', 'invoice_order_paper_size', 'invoice_order_note',
         'invoice_sale_header_image', 'invoice_sale_paper_size', 'invoice_sale_note',
      ];
      $metas = Meta::whereIn('key', $metaKeys)->pluck('value', 'key');

      $invoices = [
         'service' => [
            'header_image' => $metas['invoice_service_header_image'] ?? null,
            'paper_size' => $metas['invoice_service_paper_size'] ?? null,
            'note' => $metas['invoice_service_note'] ?? null,
         ],
         'order' => [
            'header_image' => $metas['invoice_order_header_image'] ?? null,
            'paper_size' => $metas['invoice_order_paper_size'] ?? null,
            'note' => $metas['invoice_order_note'] ?? null,
         ],
         'sale' => [
            'header_image' => $metas['invoice_sale_header_image'] ?? null,
            'paper_size' => $metas['invoice_sale_paper_size'] ?? null,
            'note' => $metas['invoice_sale_note'] ?? null,
         ],
      ];

      return inertia('Setting/Index', compact('invoices'));
   }
}
