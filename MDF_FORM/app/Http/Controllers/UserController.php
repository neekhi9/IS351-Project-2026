<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;

    

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
       $users = User::all();

         return view('users.index',compact('users'));

         
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $roles=Role::all();
       return view('users.create',compact('roles'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            // 'roles' => 'required'
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            if ($request->filled('role')) {
                $user->syncRoles($request->role);
            }

            return redirect()->route('users.index')
                             ->with('success','User created successfully');
        } catch (QueryException $e) {
            // 23000 is the SQLSTATE code for integrity constraint violation (e.g., duplicate)
            if ($e->getCode() === '23000') {
                return back()->withErrors(['email' => 'The email has already been taken.'])->withInput();
            }
            return back()->withErrors(['error' => 'Unable to create user.'])->withInput();
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $user = User::find($id);
        return view('users.show',compact('user'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
      $user = User::find($id);
      $roles=Role::all();
      return view('users.edit',compact('user','roles'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'profile_photo' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        try {
            $user = User::findOrFail($id);
            $user->name = $request->input('name');
            $user->email = $request->input('email');

            if ($request->hasFile('profile_photo')) {
                $file = $request->file('profile_photo');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('profile-photos', $fileName, 'public');
                $url = Storage::url($path);
                $user->profile_photo_path = $url;
            }

            $user->save();

            if ($request->filled('role')) {
                $user->syncRoles($request->role);
            }

            return redirect()->route('users.index')->with('success', 'User updated successfully');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return back()->withErrors(['email' => 'The email has already been taken.'])->withInput();
            }
            return back()->withErrors(['error' => 'Unable to update user.'])->withInput();
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => 'Unexpected error: ' . $e->getMessage()])->withInput();
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
    }

    public function profile(): View
    {
        $user = auth()->user();
        return view('users.profile', compact('user'));
    }

    public function updateProfilePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        try {
            $file = $request->file('profile_photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('profile-photos', $fileName, 'public'); // store on public disk
            $url = Storage::url($path);

            $user = auth()->user();
            $user->profile_photo_path = $url;
            $user->save();

            return back()->with('success', 'Profile photo updated successfully');
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to upload profile photo: ' . $e->getMessage());
        }
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = auth()->user();
        $user->name = $request->input('name');
        $user->save();

        return back()->with('success', 'Profile updated successfully');
    }
}
