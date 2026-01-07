<?php

namespace App\Http\Controllers;

use App\Models\Resort;
use Illuminate\Http\Request;

class ResortController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $region = trim((string) $request->query('region', ''));

        $query = Resort::query();

        if ($region !== '' && $region !== 'ALL') {
            $query->where('region', $region);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $resorts = $query->orderBy('id', 'desc')->get();

        $regions = Resort::query()
            ->whereNotNull('region')
            ->where('region', '!=', '')
            ->distinct()
            ->orderBy('region')
            ->pluck('region');

        return view('home', [
            'resorts' => $resorts,
            'regions' => $regions,
            'selectedRegion' => $region === '' ? 'ALL' : $region,
            'searchQuery' => $search,
        ]);
    }
}
