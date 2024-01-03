<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use DB;
class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('settings.permission.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data=[];
        $role = Role::findById($request->role_id);
        foreach($request->permission as $val){
            $data[]=$val;
        }
        DB::table('role_has_permissions')->where('role_id',$request->role_id)->delete();
        $permission=Permission::whereIn('name',$data)->get();
        $role->givePermissionTo($permission);
        return back()->with('message', 'Permission Assigned Successfully..!!');;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role=Role::find($id)->name;
        $role_has_permission=DB::table('role_has_permissions')->select("name")
        ->join('permissions','role_has_permissions.permission_id',"=","permissions.id")->where('role_id',$id)->get();
        $permissions=[];
        foreach($role_has_permission as $permission){
            $permissions[]=$permission->name;
        }
        return view('settings.permission.index',compact('id','role','permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
