@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center bg-light">{{ __('Registrar incidencia') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{route('incident.store')}}">
                        @csrf

                        <div class="form-group row">
                            <label for="title" class="col-md-4 col-form-label text-md-right">{{ __('Titulo') }}</label>

                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required autocomplete="title" autofocus>

                                @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="message_reply" class="col-md-4 col-form-label text-md-right">{{ __('Mensaje') }}</label>

                            <div class="col-md-6">
                                <textarea name="message_reply" id="message_reply" cols="30" rows="10" class="form-control @error('message_reply') is-invalid @enderror" value="{{ old('message_reply') }}" required autocomplete="message_reply" autofocus style="resize: none;"></textarea>

                                @error('message_reply')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4 d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Enviar') }}
                                </button>

                                <a href="{{route('user.index')}}" class="btn btn-secondary">
                                    {{ __('Volver al inicio') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection