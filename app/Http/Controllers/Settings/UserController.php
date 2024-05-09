<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\NotificationService;
use Auth;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(NotificationService $notificationService){
        $this->notificationService=$notificationService;
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="group-checkable" value="">';
                })
                ->addColumn('role', function ($row) {
                    return $row->role->name;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                Action
                              <span class="sr-only">Toggle Dropdown</span></button>
                              <div class="dropdown-menu" role="menu" style="">
                              <a class="dropdown-item" href="'.route('user.edit',$row->id).'"><i class="fas fa-edit"></i> Edit</a>
                              <a class="dropdown-item text-danger del_rec" href="javascript:void(0)" data-id="'.$row->id.'" data-action="'.url('settings/user-management/user').'"><i class="fas fa-trash"></i> Delete</a>
                              </div>

                          </div>';

                    return $btn;
                })
                ->rawColumns(['action', 'checkbox','role'])
                ->make(true);
        }
        return view('settings.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $result= User::find($id);
        return view('settings.users.edit',compact('result'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:16'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'role_id' => ['required','int'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $data=$request->except(['_token','_method','password_confirmation']);
        if(!empty($data['password'])){
            $data['password'] = Hash::make($data['password']);
        }
        DB::beginTransaction();
        try{
            $user=User::where('id',$request->id)->update($data);
            $role=Role::find($request->input('role_id'));
            $user=User::where('id',$request->id)->first();
            $user->assignRole($role->name);
            DB::commit();

            $request->session()->flash('message', "User Created Successfully..!!");
            return redirect()->back();
        }catch(QueryException $e){
                DB::rollBack();
                dd($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return User::destroy($id);
    }

    public function store_token(Request $request)
    {
        Auth::user()->device_token = $request->token;
        Auth::user()->save();

        return response()->json(['Token successfully stored.']);
    }

    public function sendNotification(Request $request)
    {
        $this->notificationService->send_notification($request, 17);
    }
    public function save_user(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => ['required','int'],
            'mobile' => ['required', 'string', 'max:16'],
        ]);
        $data=$request->all();
        DB::beginTransaction();
        try{
            $user=User::create($data);
            $role=Role::find($request->input('role_id'));
            $user->assignRole($role->name);
            DB::commit();

            $request->session()->flash('message', "User Created Successfully..!!");
            return redirect()->back();
        }catch(QueryException $e){
                DB::rollBack();
                dd($e);
        }

    }
}
