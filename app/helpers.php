<?php

use App\Models\Costumer;
use App\Models\DepositAccount;
use App\Models\Jewelry;
use App\Models\Order;
use App\Models\Sale;
use App\Models\Service;
use App\Models\Supplier;
use App\Models\User;

if (!function_exists('generateItemNumber')) {
    function generateItemNumber($type): string
    {
        if (!in_array($type, ['jewelry', 'employee', 'costumer', 'supplier', 'deposit-account'])) {
            throw new \InvalidArgumentException('Invalid type', 400);
        }

        switch ($type) {
            case 'jewelry':
                $prefix = '1';
                $lastItem = Jewelry::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->orderBy('id', 'desc')->first();
                $lastItem = $lastItem ? (int) substr($lastItem->jewelry_code, -3) : 0;
                break;

            case 'employee':
                $prefix = '2';
                $lastItem = User::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->orderBy('id', 'desc')->first();
                $lastItem = $lastItem ? (int) substr($lastItem->user_code, -3) : 0;
                break;

            case 'costumer':
                $prefix = '3';
                $lastItem = Costumer::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->orderBy('id', 'desc')->first();
                $lastItem = $lastItem ? (int) substr($lastItem->costumer_code, -3) : 0;
                break;

            case 'supplier':
                $prefix = '4';
                $lastItem = Supplier::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->orderBy('id', 'desc')->first();
                $lastItem = $lastItem ? (int) substr($lastItem->supplier_code, -3) : 0;
                break;

            case 'deposit-account':
                $prefix = '5';
                $lastItem = DepositAccount::latest()->first();
                $lastItem = $lastItem ? (int) substr($lastItem->account_number, -3) : 0;
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
                $totalThisMonth = Service::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
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

if (!function_exists('getOrderTable')) {
    function getOrderTable($order, $defaultColumn = 'id', $defaultDirection = 'DESC'): array
    {
        $column = $defaultColumn;
        $direction = $defaultDirection;

        if ($order) {
            $sign = $order[0];
            $column = ltrim($order, '+-');
            $column = str_replace('.', '_', $column);
            $direction = $sign === '-' ? 'ASC' : 'DESC';
        }

        return compact('column', 'direction');
    }
}
