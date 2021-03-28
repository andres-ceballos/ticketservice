@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center bg-light">{{ __('Incidencias realizadas') }}</div>

                <div class="card-body">

                    <div class="mb-3">
                        <a href="{{route('incident.create')}}" class="btn btn-md btn-primary">Registrar incidencia</a>
                    </div>

                    <table class="table table-striped table-bordered table-responsive-md">
                        <thead class="thead-dark">
                            <tr class="text-center">
                                <th class="align-middle" scope="col">Titulo</th>
                                <th class="align-middle" scope="col">Último mensaje</th>
                                <th class="align-middle" scope="col">Técnico a cargo</th>
                                <th class="align-middle" scope="col">Calif. servicio</th>
                                <th class="align-middle" scope="col">Fecha creación</th>
                                <th class="align-middle" colspan="2">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($user_incidents))
                            @foreach($user_incidents as $user_incident)
                            <tr class="text-center">
                                <td class="align-middle">{{ $user_incident->title }}</td>
                                <td class="align-middle" style="min-width: 20rem; max-width: 20rem;">
                                    <div class="d-flex">
                                        <div id="message-tech-{{$user_incident->id}}" class="col-10 text-truncate">
                                            {{ $user_incident->message_reply }}
                                        </div>
                                        @if($user_incident->notification_user)
                                        <span id="message-notification-{{$user_incident->id}}" class="bg-primary mx-2 px-2 text-white rounded-pill">{{ $user_incident->notification_user }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="align-middle tech-assigned-{{$user_incident->id}}" style="min-width: 10rem; max-width: 10rem;">
                                    {{ $user_incident->tech_name }}
                                </td>
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
                                <td class="align-middle text-center">
                                    <a href="{{route('detail-incident.show', $user_incident->id)}}" class="btn btn-md btn-primary btn-edit">Ver</a>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr class="text-center">
                                <td class="align-middle" colspan="12">No has registrado incidencias.</td>
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