<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\projectDetail;
use App\Models\Design;
use App\Models\Package;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    public function dashboardView()
    {
        if (Gate::denies('admin-staff-doctor-access', ['dashboard', "view"])) {
            abort(403, "You have no access to view the dashboard");
        }
        $userName = Auth::user()->name;
        

        return view('dash', compact('userName'));
      
    }
}
