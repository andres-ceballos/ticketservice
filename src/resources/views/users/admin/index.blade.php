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
                                <th class="align-middle" scope="col">#</th>
                                <th class="align-middle" scope="col">Nombres</th>
                                <th class="align-middle" scope="col">Apellidos</th>
                                <th class="align-middle" scope="col">Correo</th>
                                <th class="align-middle" scope="col">Ext. Teléfono</th>
                                <th class="align-middle" scope="col">Rol</th>
                                <th class="align-middle" colspan="2">
                                    <a href="{{route('admin.create')}}" class="btn btn-md btn-primary w-100">Crear usuario</a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr class="text-center">
                                <th class="align-middle" scope="row">{{ $user->id }}</th>
                                <td class="align-middle">{{ $user->firstname }}</td>
                                <td class="align-middle">{{ $user->lastname }}</td>
                                <td class="align-middle">{{ $user->email }}</td>
                                <td class="align-middle">{{ $user->phone_ext }}</td>
                                <td class="align-middle">{{ $user->role_name }}</td>
                                <td class="align-middle" class="text-center">
                                    <a href="{{route('admin.edit', $user->id)}}" class="btn btn-md btn-primary btn-edit" data-toggle="modal" data-target="#edit-user" data-user="{{ json_encode($user) }}">Editar</a>
                                </td>
                                <td class="align-middle" class="text-center">
                                    <form onsubmit="return confirm('¿Estás seguro de querer eliminar el registro de {{$user->firstname}}?')" action="{{route('admin.destroy', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-md btn-danger btn-delete">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <nav>
                        <ul class="pagination justify-content-end">
                            <li>
                                {{ $users->links('pagination::bootstrap-4') }}
                            </li>
                        </ul>
                    </nav>

                    @include('users.admin.edit')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection