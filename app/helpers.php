<?php

use App\Models\Costumer;
use App\Models\Jewelry;
use App\Models\Order;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\User;

if (!function_exists('generateItemNumber')) {
    function generateItemNumber($type): string
    {
        if (!in_array($type, ['jewelry', 'employee', 'costumer', 'supplier'])) {
            throw new \InvalidArgumentException('Invalid type', 400);
        }

        switch ($type) {
            case 'jewelry':
                $prefix = '1';
                $lastItem = Jewelry::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->orderBy('id', 'desc')->count();
                break;

            case 'employee':
                $prefix = '2';
                $lastItem = User::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->orderBy('id', 'desc')->where('role', 'SALES')->count();
                break;

            case 'costumer':
                $prefix = '3';
                $lastItem = Costumer::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->orderBy('id', 'desc')->count();
                break;

            case 'supplier':
                $prefix = '4';
                $lastItem = Supplier::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->orderBy('id', 'desc')->count();
                break;

            default:
                $prefix = '0';
                $lastItem = 0;
                break;
        }

        $date = now()->format('my');
        $lastItem = str_pad($lastItem + 1, 3, '0', STR_PAD_LEFT);
        return $date . $prefix .  $lastItem;
    }
}


if (!function_exists('generateInvoiceNumber')) {
    function generateInvoiceNumber($type): string
    {
        if (!in_array($type, ['sale', 'order', 'service'])) {
            throw new \InvalidArgumentException('Invalid type', 400);
        }

        switch ($type) {
            case 'sale':
                $prefix = 'SL';
                $totalThisMonth = Sale::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
                break;

            case 'order':
                $prefix = 'OD';
                $totalThisMonth = Order::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
                break;

            case 'service':
                $prefix = 'SV';
                $totalThisMonth = Order::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
                break;

            default:
                $prefix = '0';
                $lastItem = 0;
                break;
        }

        $date = now()->format('ymd');
        $lastItem = str_pad($totalThisMonth + 1, 3, '0', STR_PAD_LEFT);
        $randomNumber = rand(1000, 9999);

        return 'INV/' . $date . '/' . $prefix . '/' . $randomNumber . $lastItem;
    }
}

if (!function_exists('convertImageToBase64')) {
    function convertImageToBase64($imagePath): string
    {
        $type = pathinfo($imagePath, PATHINFO_EXTENSION);
        $data = file_get_contents($imagePath);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
}
