<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = DB::table('users')->orderBy('id', 'DESC')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->select(
                'users.*',
                'roles.role_name'
            )->paginate(5);

        return view('users.admin.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.admin.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        $user = $request->validated();
        User::create($user);

        return redirect()->back()->with('success', 'Usuario registrado correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        //IF PASSWORD ADD IN FORM IS EQUALS TO PASSWORD IN DATABASE
        if (Hash::check($request->password, $user->password)) {
            return redirect()->back()->with('error', 'La contraseña es igual a la anterior.<br> Intenta de nuevo con una totalmente diferente.');
            #
            //IF FIELD PASSWORD WAS ADDED... UPDATE ALL
        } else if ($request->filled('password')) {
            $user->update($request->all());

            return redirect()->back()->with('success', 'Todos los datos del usuario han sido actualizados.');
            #
            //IF FIELD PASSWORD WAS OMITTED... UPDATE ALL, EXCEPT PASSWORD
        } else {
            $user->update($request->except([
                'password',
                'password_confirmation'
            ]));

            return redirect()->back()->with('success', 'Datos de usuario actualizados correctamente.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Usuario eliminado correctamente');
    }

    public function destroyAll(Request $request)
    {
        $ids = explode(',', $request->ids);
        DB::table('users')->whereIn('id', $ids)->delete();
        return response()->json(['success' => 'Usuarios eliminados correctamente']);
    }
}
