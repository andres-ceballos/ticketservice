@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center bg-light">{{ __('Incidencias realizadas') }}</div>

                <div class="card-body">

                    <div class="mb-3">
                        <a href="{{route('incident.create')}}" class="btn btn-md btn-primary w-25">Registrar incidencia</a>
                    </div>

                    <table class="table table-striped table-bordered table-responsive-sm">
                        <thead class="thead-dark">
                            <tr class="text-center">
                                <th scope="col">Titulo</th>
                                <th scope="col">Último mensaje</th>
                                <th scope="col">Técnico a cargo</th>
                                <th scope="col">Calif. servicio</th>
                                <th scope="col">Fecha creación</th>
                                <th colspan="2">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user_incidents as $user_incident)
                            <tr class="text-center">
                                <td>{{ $user_incident->title }}</td>
                                <td style="max-width: 20rem;">
                                    <div class="col-12 text-truncate">
                                        {{ $user_incident->message_reply }}
                                    </div>
                                </td>
                                <td>{{ $user_incident->tech_name }}</td>
                                <td>{{ $user_incident->service_rating}}</td>
                                <td>{{ $user_incident->created_at }}</td>
                                <td class="text-center">
                                    <a href="{{route('detail-incident.show', $user_incident->id)}}" class="btn btn-md btn-primary btn-edit">Ver</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection