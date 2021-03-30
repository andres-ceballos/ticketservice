@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center bg-light">{{ __('Calificación del servicio') }}</div>

                <div class="card-body d-flex justify-content-center">
                    <div class="border px-3 py-5 mb-3 rounded text-center">
                        <div class="stars">
                            <form id="form-service-rating" action="">
                                <input class="checkbox-star star star-5" id="star-5" type="radio" name="star_rating" value="5" />
                                <label class="checkbox-star star star-5" for="star-5"></label>
                                <input class="checkbox-star star star-4" id="star-4" type="radio" name="star_rating" value="4" />
                                <label class="checkbox-star star star-4" for="star-4"></label>
                                <input class="checkbox-star star star-3" id="star-3" type="radio" name="star_rating" value="3" />
                                <label class="checkbox-star star star-3" for="star-3"></label>
                                <input class="checkbox-star star star-2" id="star-2" type="radio" name="star_rating" value="2" />
                                <label class="checkbox-star star star-2" for="star-2"></label>
                                <input class="checkbox-star star star-1" id="star-1" type="radio" name="star_rating" value="1" />
                                <label class="checkbox-star star star-1" for="star-1"></label>
                                <input type="hidden" name="action" value="update_service_rating">
                            </form>
                        </div>
                    </div>
                </div>
                <!--.card-body-->

                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-md btn-primary btn-rating" form="form-service-rating">Enviar calificación</button>
                </div>
                <!--.card-footer-->
            </div>
            <!--.card-->
        </div>
    </div>
</div>
@endsection