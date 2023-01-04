<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\AuthHelper;
use Illuminate\Http\Request;
use App\DataTables\UsersDataTable;
use App\Http\Requests\UserRequest;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function index(UsersDataTable $dataTable)
    // {
    //     $pageTitle = trans('global-message.list_form_title',['form' => trans('users.title')] );
    //     $auth_user = AuthHelper::authSession();
    //     $assets = ['data-table'];
    //     $headerAction = '<a href="'.route('users.create').'" class="btn btn-sm btn-primary" role="button">Add User</a>';
    //     return $dataTable->render('global.datatable', compact('pageTitle','auth_user','assets', 'headerAction'));
    // }

    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create()
    // {
    //     $roles = Role::where('status',1)->get()->pluck('title', 'id');

    //     return view('users.form', compact('roles'));
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(UserRequest $request)
    // {
    //     $request['password'] = bcrypt($request->password);

    //     $request['username'] = $request->username ?? stristr($request->email, "@", true) . rand(100,1000);

    //     $user = User::create($request->all());

    //     storeMediaFile($user,$request->profile_image, 'profile_image');

    //     $user->assignRole('user');

    //     // Save user Profile data...
    //     $user->userProfile()->create($request->userProfile);

    //     return redirect()->route('users.index')->withSuccess(__('message.msg_added',['name' => __('users.store')]));
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($id != Auth::id()){
            throw ValidationException::withMessages(['akses' => 'Anda tidak memiliki akses ke halaman ini.']);
            return redirect()->route('index');
        }

        $data = User::findOrFail($id);

        return view('users.profile', compact('data'));
    }


    public function password($id)
    {
        if($id != Auth::id()){
            throw ValidationException::withMessages(['akses' => 'Anda tidak memiliki akses ke halaman ini.']);
            return redirect()->route('index');
        }

        return view('users.password');
    }

    public function password_update(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = User::findOrFail(Auth::id());

        if(Hash::check($request->old_password, $user->password)){

            $user->fill([
                'password' => Hash::make($request->password)
                ])->save();

            return redirect()->back()->withSuccess('Password berhasil diubah');
        }
        else{
            return redirect()->back()->withErrors('Password tidak sesuai');
        }

        // $user->fill($request->all())->update();

    }



    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit($id)
    // {
    //     $data = User::with('userProfile','roles')->findOrFail($id);

    //     $data['user_type'] = $data->roles->pluck('id')[0] ?? null;

    //     $roles = Role::where('status',1)->get()->pluck('title', 'id');

    //     $profileImage = getSingleMedia($data, 'profile_image');

    //     return view('users.form', compact('data','id', 'roles', 'profileImage'));
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $changeEmail = false;
        // dd($request->all());
        $user = User::findOrFail($id);

        if($user->email == $request->email){
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
            ]);
        }
        else{
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
            ]);
            $changeEmail = true;
        }

        // $request['password'] = $request->password != '' ? bcrypt($request->password) : $user->password;
        $user->fill($request->all())->update();

        if($request->gender == "null"){
            $user->gender = NULL;
        }

        if($changeEmail == true){
            $user->email_verified_at = NULL;
        }

        $user->save();
        return redirect()->back()->withSuccess('Profil berhasil diubah');
    }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy($id)
    // {
    //     $user = User::findOrFail($id);
    //     $status = 'errors';
    //     $message= __('global-message.delete_form', ['form' => __('users.title')]);

    //     if($user!='') {
    //         $user->delete();
    //         $status = 'success';
    //         $message= __('global-message.delete_form', ['form' => __('users.title')]);
    //     }

    //     if(request()->ajax()) {
    //         return response()->json(['status' => true, 'message' => $message, 'datatable_reload' => 'dataTable_wrapper']);
    //     }

    //     return redirect()->back()->with($status,$message);

    // }
}
