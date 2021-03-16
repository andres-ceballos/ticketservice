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
                                <th class="align-middle" scope="col">Titulo</th>
                                <th class="align-middle" scope="col">Último mensaje</th>
                                <th class="align-middle" scope="col">Usuario</th>
                                <th class="align-middle" scope="col">Atendido</th>
                                <th class="align-middle" scope="col">Estado</th>
                                <th class="align-middle" scope="col">Calif. servicio</th>
                                <th class="align-middle" scope="col">Fecha creación</th>
                                <th class="align-middle" colspan="2">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody class="table-body">
                            <!--IF TBODY -->
                            @if(count($user_incidents))
                            @foreach($user_incidents as $user_incident)
                            <tr class="text-center">
                                <td class="align-middle">{{ $user_incident->title }}</td>
                                <td class="align-middle" style="min-width: 20rem; max-width: 20rem;">
                                    <div class="d-flex">
                                        <div id="message-user-{{$user_incident->id}}" class="col-10 text-truncate">
                                            {{ $user_incident->message_reply }}
                                        </div>
                                        @if($user_incident->notification_tech)
                                        <span id="message-notification-{{$user_incident->id}}" class="bg-primary mx-2 px-2 text-white rounded-pill">{{ $user_incident->notification_tech }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="align-middle">{{ $user_incident->user_name }}</td>
                                <!--IF FIELD ATTENDED--- -->
                                @if ($user_incident->tech_id)
                                <td class="align-middle">
                                    <p class="m-0">SI</p>
                                </td>
                                @else
                                <td class="align-middle">
                                    <p class="m-0">NO</p>
                                </td>
                                @endif
                                <!-- CLOSE FIELD ATTENDED -->

                                <!-- IF FIELD STATUS -->
                                @if ($user_incident->incident_status == 0)
                                <td class="align-middle">
                                    <small>
                                        <p class="m-0 bg-danger border border-danger py-1 px-1 rounded-lg ">SIN REALIZAR</p>
                                    </small>
                                </td>
                                @else
                                <td class="align-middle">
                                    <p class="m-0 bg-success border border-success py-2 px-1 rounded-lg ">HECHO</p>
                                </td>
                                @endif
                                <td class="align-middle">{{ $user_incident->service_rating}}</td>
                                <td class="align-middle">{{ $user_incident->created_at }}</td>
                                <!-- CLOSE FIELD STATUS -->

                                <!-- IF TECH ATTENDED INCIDENT -->
                                @if ($user_incident->tech_id)
                                <td class="align-middle text-center">
                                    <a href="{{route('detail-incident.show', $user_incident->id)}}" class="btn btn-md btn-primary btn-edit w-100">Ver</a>
                                </td>

                                <td class="align-middle">
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
                                <td class="align-middle">
                                    <form action="{{route('incident.update', $user_incident->id)}}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="action" value="update_tech_id">
                                        <button class="btn btn-md btn-secondary">Aceptar</button>
                                    </form>
                                </td>
                                <td class="align-middle">&nbsp;</td>
                                @endif
                                <!-- CLOSE TECH ATTENDED INCIDENT -->
                            </tr>
                            @endforeach
                            <!--ELSE TBODY -->
                            @else
                            <tr class="text-center">
                                <td class="align-middle" colspan="12">No se han registrado incidencias.</td>
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