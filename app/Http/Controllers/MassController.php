<?php

namespace App\Http\Controllers;

use App\Models\MassWeekday;
use App\Models\MassSunday;
use Illuminate\Http\Request;

class MassController extends Controller
{
    public function index()
    {
        $weekdayMasses = MassWeekday::where('status', 'Active')->orderBy('day')->orderBy('mass_time')->get();
        $sundayMasses = MassSunday::where('status', 'Active')->orderBy('mass_time')->get();

        return view('mass.index', compact('weekdayMasses', 'sundayMasses'));
    }

    public function storeWeekday(Request $request)
    {
        $validated = $request->validate([
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'mass_type' => 'required|in:AM,PM',
            'mass_time' => 'required',
            'description' => 'nullable|string|max:255',
        ]);

        MassWeekday::create($validated);

        return redirect()->route('mass.index')->with('success', 'Weekday mass added successfully.');
    }

    public function storeSunday(Request $request)
    {
        $validated = $request->validate([
            'mass_name' => 'required|string|max:255',
            'mass_time' => 'required',
            'description' => 'nullable|string|max:255',
        ]);

        MassSunday::create($validated);

        return redirect()->route('mass.index')->with('success', 'Sunday mass added successfully.');
    }

    public function updateWeekday(Request $request, MassWeekday $massWeekday)
    {
        $validated = $request->validate([
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'mass_type' => 'required|in:AM,PM',
            'mass_time' => 'required',
            'description' => 'nullable|string|max:255',
        ]);

        $massWeekday->update($validated);

        return redirect()->route('mass.index')->with('success', 'Weekday mass updated successfully.');
    }

    public function updateSunday(Request $request, MassSunday $massSunday)
    {
        $validated = $request->validate([
            'mass_name' => 'required|string|max:255',
            'mass_time' => 'required',
            'description' => 'nullable|string|max:255',
        ]);

        $massSunday->update($validated);

        return redirect()->route('mass.index')->with('success', 'Sunday mass updated successfully.');
    }

    public function archiveWeekday(MassWeekday $massWeekday)
    {
        $massWeekday->update(['status' => 'Archived']);
        return redirect()->route('mass.index')->with('success', 'Weekday mass archived.');
    }

    public function archiveSunday(MassSunday $massSunday)
    {
        $massSunday->update(['status' => 'Archived']);
        return redirect()->route('mass.index')->with('success', 'Sunday mass archived.');
    }
}
