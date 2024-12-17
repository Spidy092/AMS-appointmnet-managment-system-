<?php

namespace App\Http\Controllers;

use App\Models\ClinicDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicSettingsController extends Controller
{
    public function show(Request $request)
    {
        $clinics = Auth::user()->clinics;  // change in feture for staff

        // Find the selected clinic with its related clinic setting
        $selectedClinic = null;
        if ($request->clinic_id) {
            $selectedClinic = ClinicDetail::with('clinicSetting')->find($request->clinic_id);
        }



        return view('settingsClinic', compact('clinics', 'selectedClinic'));
    }

    /**
     * Update the settings for the selected clinic.
     */
    public function update(Request $request)
    {
        $request->validate([
            'clinic_id' => 'required|exists:mf_clinic_details,id',
            'time_slots' => 'required|integer|min:1',
            'remainder_to_patient' => 'required|boolean',
        ]);

        $clinic = ClinicDetail::findOrFail($request->clinic_id);

        $clinic->clinicSetting()->updateOrCreate(
            ['clinic_id' => $clinic->id],
            [
                'time_slot_minutes' => $request->time_slots,
                'remainder_to_patient' => $request->remainder_to_patient,
            ]
        );

        return redirect()->route('clinic.settings.show', ['clinic_id' => $request->clinic_id])
            ->with('success', 'Clinic settings updated successfully!');
    }
}
