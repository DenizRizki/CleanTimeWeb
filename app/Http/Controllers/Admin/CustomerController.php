<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $customers = Customer::with('user')
            ->whereHas('user', function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%");
            })
            ->orWhere('phone', 'like', "%$search%")
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString(); 

        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|min:3|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6', 
            'phone'    => 'required|numeric|digits_between:10,15', 
            'address'  => 'required|string|min:5', 
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'customer',
            ]);

            Customer::create([
                'user_id' => $user->id,
                'phone'   => $request->phone,
                'address' => $request->address,
            ]);

            DB::commit();

            return redirect()->route('admin.customers.index')
                             ->with('success', 'Pelanggan baru dan akun aplikasi berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                             ->withInput()
                             ->withErrors(['error' => 'Gagal mendaftarkan pelanggan: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $customer = Customer::with('user')->findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }

    public function edit($id)
    {
        $customer = Customer::with('user')->findOrFail($id);
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $user = User::findOrFail($customer->user_id);
        $request->validate([
            'name'     => 'required|string|min:3|max:255', 
            'email'    => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'phone'    => 'required|numeric|digits_between:10,15', 
            'address'  => 'required|string|min:5',
        ]);

        DB::beginTransaction();

        try {
            $userData = [
                'name'  => $request->name,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);
            $customer->update([
                'phone'   => $request->phone,
                'address' => $request->address,
            ]);

            DB::commit();

            return redirect()->route('admin.customers.index')
                             ->with('success', 'Data pelanggan ' . $request->name . ' berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal mengubah data: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        
        DB::beginTransaction();
        try {
            User::where('id', $customer->user_id)->delete();
            $customer->delete();
            DB::commit();

            return redirect()->route('admin.customers.index')->with('success', 'Pelanggan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.customers.index')->with('error', 'Gagal menghapus pelanggan.');
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|string',
        ]);

        $ids = array_filter(explode(',', $request->selected_ids), 'is_numeric');

        if (count($ids) > 0) {
            DB::beginTransaction();
            try {
                $userIds = Customer::whereIn('id', $ids)->pluck('user_id');
                User::whereIn('id', $userIds)->delete();
                Customer::whereIn('id', $ids)->delete();

                DB::commit();

                return redirect()->route('admin.customers.index')
                                 ->with('success', count($ids) . ' pelanggan dan akun aplikasi berhasil dihapus massal.');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('admin.customers.index')->with('error', 'Gagal melakukan hapus massal.');
            }
        }

        return redirect()->route('admin.customers.index');
    }
}