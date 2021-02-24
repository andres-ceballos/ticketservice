@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center bg-light">{{ __('Listado de incidencias') }}</div>

                <div class="card-body">

                    <table class="table table-striped table-bordered table-responsive-sm">
                        <thead class="thead-dark">
                            <tr class="text-center">
                                <th scope="col">Titulo</th>
                                <th scope="col">Último mensaje</th>
                                <th scope="col">Usuario</th>
                                <th scope="col">Atendido</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Calif. servicio</th>
                                <th scope="col">Fecha creación</th>
                                <th colspan="2">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($user_incidents))
                            @foreach($user_incidents as $user_incident)
                            <tr class="text-center">
                                <td>{{ $user_incident->title }}</td>
                                <td style="max-width: 18rem;">
                                    <div class="col-12 text-truncate">
                                        {{ $user_incident->message_reply }}
                                    </div>
                                </td>
                                <td>{{ $user_incident->user_name }}</td>
                                @if ($user_incident->tech_id)
                                <td>
                                    <p class="m-0">SI</p>
                                </td>
                                @else
                                <td>
                                    <p class="m-0">NO</p>
                                </td>
                                @endif

                                @if ($user_incident->incident_status == 0)
                                <td>
                                    <small>
                                        <p class="m-0 bg-danger border border-danger py-1 px-1 rounded-lg ">SIN REALIZAR</p>
                                    </small>
                                </td>
                                @else
                                <td>
                                    <p class="m-0 bg-success border border-success py-2 px-1 rounded-lg ">HECHO</p>
                                </td>
                                @endif
                                <td>{{ $user_incident->service_rating}}</td>
                                <td>{{ $user_incident->created_at }}</td>

                                @if ($user_incident->tech_id)
                                <td>
                                    <a href="{{route('detail-incident.show', $user_incident->id)}}" class="btn btn-md btn-primary btn-edit w-100">Ver</a>
                                </td>

                                <td>
                                    @if ($user_incident->incident_status == 0)
                                    <form action="{{route('incident.update', $user_incident->id)}}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="action" value="update_incident_status">
                                        <button class="btn btn-md btn-danger">Cerrar</button>
                                    </form>
                                    @endif
                                </td>
                                @else
                                <td>
                                    <form action="{{route('incident.update', $user_incident->id)}}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="action" value="update_tech_id">
                                        <button class="btn btn-md btn-secondary">Aceptar</button>
                                    </form>
                                </td>
                                <td>&nbsp;</td>
                                @endif
                            </tr>
                            @endforeach
                            @else
                            <tr class="text-center">
                                <td colspan="12">No se han registrado incidencias.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection