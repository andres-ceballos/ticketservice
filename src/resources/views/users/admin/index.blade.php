@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center bg-light">{{ __('Listado de usuarios') }}</div>

                <div class="card-body">
                    <table class="table table-striped table-bordered table-responsive-sm">
                        <thead class="thead-dark">
                            <tr class="text-center">
                                <th scope="col">#</th>
                                <th scope="col">Nombres</th>
                                <th scope="col">Apellidos</th>
                                <th scope="col">Correo</th>
                                <th scope="col">Ext. Teléfono</th>
                                <th scope="col">Rol</th>
                                <th colspan="2">
                                    <a href="{{route('admin.create')}}" class="btn btn-md btn-primary w-100">Crear usuario</a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr class="text-center">
                                <th scope="row">{{ $user->id }}</th>
                                <td>{{ $user->firstname }}</td>
                                <td>{{ $user->lastname }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone_ext }}</td>
                                <td>{{ $user->role_name }}</td>
                                <td class="text-center">
                                    <a href="{{route('admin.edit', $user->id)}}" class="btn btn-md btn-primary btn-edit" data-toggle="modal" data-target="#edit-user" data-user="{{ json_encode($user) }}">Editar</a>
                                </td>
                                <td class="text-center">
                                    <form action="{{route('admin.destroy', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-md btn-danger btn-delete">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @include('users.admin.edit')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection