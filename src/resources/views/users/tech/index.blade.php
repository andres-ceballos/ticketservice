@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center bg-light">{{ __('Listado de incidencias') }}</div>

                <div class="card-body">

                    <table class="table table-striped table-bordered table-responsive-md">
                        <thead class="thead-dark">
                            <tr class="text-center">
                                <th class="align-middle" scope="col">Titulo</th>
                                <th class="align-middle" scope="col">Último mensaje</th>
                                <th class="align-middle" scope="col">Usuario</th>
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
                                <td class="align-middle" style="min-width: 18rem; max-width: 18rem;">
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

                                <!-- IF FIELD STATUS -->
                                @if ($user_incident->incident_status == 0)
                                <td class="align-middle">
                                    <small>
                                        <p class="m-0 bg-danger border border-danger py-1 px-1 rounded-lg ">SIN REALIZAR</p>
                                    </small>
                                </td>
                                @else
                                <td class="align-middle">
                                    <small>
                                        <p class="m-0 bg-success border border-success py-1 px-1 rounded-lg ">HECHO</p>
                                    </small>
                                </td>
                                @endif
                                <td class="align-middle" style="min-width: 7rem; max-width: 7rem;">
                                    <div class="star-rating-{{$user_incident->id}}">
                                        @if($user_incident->service_rating > 0)
                                            @for($i = 1; $i <= 5; $i++) 
                                                @if ($i <=$user_incident->service_rating)
                                                    <i class="p-0 fa fa-star" style="color:orange"></i>
                                                @else
                                                    <i class="p-0 fa fa-star"></i>
                                                @endif
                                            @endfor
                                        @else
                                            {{ __('Sin calif.') }}
                                        @endif
                                    </div>
                                </td>
                                <td class="align-middle">
                                    {{ \Carbon\Carbon::parse($user_incident->created_at)->format('d/M/Y') }}
                                </td>
                                <!-- CLOSE FIELD STATUS -->

                                <!-- IF TECH ATTENDED INCIDENT -->
                                @if ($user_incident->tech_id)
                                <td class="align-middle text-center">
                                    <a href="{{route('detail-incident.show', $user_incident->id)}}" class="btn btn-md btn-primary btn-edit w-100">Ver</a>
                                </td>

                                <td class="align-middle">
                                    @if ($user_incident->incident_status == 0)
                                    <form onsubmit="return confirm('¿Estás seguro de querer cerrar la solicitud de {{$user_incident->user_name}}?')" action="{{route('incident.update', $user_incident->id)}}" method="POST">
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
                                        <button class="btn btn-md btn-secondary btn-accept-{{$user_incident->id}}">Aceptar</button>
                                    </form>
                                </td>
                                <td class="align-middle">&nbsp;</td>
                                @endif
                                <!-- CLOSE TECH ATTENDED INCIDENT -->
                            </tr>
                            @endforeach
                            <!--ELSE TBODY -->
                            @else
                            <tr class="text-center no-registers-row">
                                <td class="align-middle" colspan="12">No se han registrado incidencias.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    <div class="pagination-content">
                        <nav class="nav-pagination">
                            <ul class="pagination">
                                <li>
                                    {{ $user_incidents->onEachSide(0)->links('pagination::bootstrap-4') }}
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection