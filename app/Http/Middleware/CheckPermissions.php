<?php

// namespace App\Http\Middleware;

// use Closure;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Symfony\Component\HttpFoundation\Response;

// class CheckPermissions
// {
// public function handle(Request $request, Closure $next, $module = null, $action = null)
// {
//     $user = Auth::user();
//     if (!$user) {
//         return redirect('/login')->with('error', 'You must be logged in to access this page.');
//     }

//     $accessGroupId = $user->access_group_id;
//     if (!$accessGroupId) {
//         return redirect('/unauthorized')->with('error', 'Unauthorized access: No access group assigned.');
//     }

//     if ($module && $action) {
//         $link = \DB::table('bah_dynamic_links')->where('link_href', $module)->first();
//         if (!$link) {
//             \Log::warning('Module not found', ['module' => $module]);
//             return redirect()->back()->with('error', 'Invalid module.');
//         }

//         $permission = \DB::table('bah_access_group_permissions')
//             ->where('access_group_id', $accessGroupId)
//             ->where('link_id', $link->id)
//             ->first();

//         if (!$permission || $permission->$action != '1') {

//             if ($request->ajax()) {
//                 return response()->json([
//                     'success' => false,
//                     'message' => 'You do not have permission to perform this action.'
//                 ], 403);
//             }

//             return redirect()->back()->with('error', 'You do not have permission to perform this action.');
//         }
//     }

//     return $next($request);
// }


// }
