<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $todayRevenue = Transaction::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->where('payment_status', 'paid') 
            ->sum('total_price');
    
        $months = [];
        $revenues = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->isoFormat('MMMM'); 
            $revenues[] = Transaction::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->where('payment_status', 'paid') 
                ->sum('total_price');
        }

        $activeLaundryCount = Transaction::whereIn('status', ['antrian', 'dicuci', 'disetrika', 'siap diambil'])->count();
        $totalCustomers = Customer::count(); 
        $recentTransactions = Transaction::with(['customer.user', 'transactionDetails.service'])
    ->latest()
    ->take(5)
    ->get();

        return view('dashboard', compact(
            'todayRevenue', 
            'activeLaundryCount', 
            'totalCustomers', 
            'recentTransactions',
            'months',
            'revenues'
        ));
    }
}