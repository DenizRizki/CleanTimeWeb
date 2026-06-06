<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
  public function index(Request $request)
{
    $search = $request->search;
    $services = Service::where('service_name', 'like', "%$search%")
        ->latest()
        ->paginate(10)
        ->withQueryString();

    if ($request->wantsJson() || $request->is('api/*')) {
        return response()->json([
            'status' => 'success',
            'message' => 'Daftar layanan berhasil diambil.',
            'data' => $services
        ], 200);
    }

    return view('admin.services.index', compact('services'));
}

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
    'service_name' => 'required|string|min:3|max:20', 
    'unit'         => 'required|string|in:Kg,Pcs', 
    'price'        => 'required|numeric|min:1000',
    ]);

    $service = Service::create($validated);

    if ($request->wantsJson() || $request->is('api/*')) {
        return response()->json([
            'status' => 'success',
            'message' => 'Layanan baru berhasil ditambahkan lewat API.',
            'data' => $service
        ], 201);
    }

    return redirect()->route('admin.services.index')->with('success', 'Layanan baru berhasil ditambahkan.');
}

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.services.edit', compact('service'));
    }

   public function update(Request $request, $id)
{
    $service = Service::findOrFail($id);
    $validated = $request->validate([
        'service_name' => 'required|string|min:3|max:20', 
        'unit'         => 'required|string|in:Kg,Pcs', 
        'price'        => 'required|numeric|min:1000',
    ]);

    $service->update($validated);

    if ($request->wantsJson() || $request->is('api/*')) {
        return response()->json([
            'status' => 'success',
            'message' => 'Sebagian data layanan berhasil diperbarui.',
            'data' => $service
        ], 200);
    }

    return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil diperbarui.');
}

    public function destroy($id)
{
    $service = Service::findOrFail($id);
    $service->delete();

    if (request()->wantsJson() || request()->is('api/*')) {
        return response()->json([
            'status' => 'success',
            'message' => 'Layanan dengan ID ' . $id . ' berhasil dihapus lewat API.'
        ], 200);
    }

    return redirect()->route('admin.services.index')
                     ->with('success', 'Layanan berhasil dihapus.');
}

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|string',
        ]);

        $ids = array_filter(explode(',', $request->selected_ids), 'is_numeric');

        if (count($ids) > 0) {
            Service::whereIn('id', $ids)->delete();
            return redirect()->route('admin.services.index')->with('success', count($ids) . ' layanan berhasil dihapus massal.');
        }

        return redirect()->route('admin.services.index');
    }
}