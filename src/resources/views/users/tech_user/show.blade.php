@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center bg-light">{{ __('Conversación solicitud') }}</div>

                <div style="height: 60vh;" class="card-body d-flex justify-content-center">

                    <div class="w-75 border p-3 rounded overflow-auto d-flex flex-column-reverse">
                        <div class="flex-column">
                            @foreach($details_incident as $detail_incident)
                            <div class="chat-message-{{$detail_incident->incident_id}}">
                                @if(Auth::user()->id == $detail_incident->from_user_id)
                                <div class="d-block d-flex flex-row-reverse my-2">
                                    <div class="d-block order-2 bg-info w-50 rounded-lg px-3 py-3 pull-right">
                                        <p class="m-0">{{ $detail_incident->message_reply }}</p>
                                        <small class="d-flex flex-row-reverse">{{$detail_incident->created_at}}</small>
                                    </div>
                                </div>
                                @else
                                <div class="d-block">
                                    <div class="d-block order-1 bg-dark text-white w-50 rounded-lg px-3 py-3 my-2">
                                        <p class="m-0">{{ $detail_incident->message_reply }}</p>
                                        <small class="d-flex flex-row-reverse">{{$detail_incident->created_at}}</small>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if($detail_incident->incident_status == 0)
                <div class="btn-message-{{$detail_incident->incident_id}} card-footer d-flex justify-content-center">
                    <form id="form-message" method="POST" action="{{route('detail-incident.store')}}" class="row w-75 d-flex justify-content-between">
                        @csrf

                        <textarea name="message_reply" id="message_reply" cols="70" rows="1" style="resize: none;" placeholder="Escribe un mensaje"></textarea>
                        <button type="submit" class="btn btn-primary">{{ __('Enviar mensaje') }} </button>
                    </form>
                </div>
                @else
                <div class="card-footer d-flex justify-content-center">
                    <p>SOLICITUD CERRADA</p>
                </div>
                @endif
            </div>
            <div class="d-flex justify-content-end mt-3">
                <a href="{{route('tech.index')}}" class="btn btn-md btn-primary">Regresar</a>
            </div>
        </div>
    </div>
</div>
@endsection