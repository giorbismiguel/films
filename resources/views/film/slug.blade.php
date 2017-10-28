@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 text-center">
                <div class="card" style="border: 1px solid #ccc;">
                    <div class="card-block">
                        <img class="card-img" src="{{ asset('storage/' . $film->photo) }}" alt="Card image cap">

                        <div class="card-block">
                            <h4 class="card-title">{{ $film->name }}</h4>
                            <p class="card-text">{{ $film->description }}</p>
                        </div>

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Rating: {{ $film->rating }}</li>
                            <li class="list-group-item">Ticket Price: {{ $film->ticket_price }}</li>
                            <li class="list-group-item">Release At: {{ $releaseAt->format('m/d/Y H:i:s') }}</li>
                            <li class="list-group-item">Country: {{ $countryName }}</li>
                            <li class="list-group-item">Gender(s): {{ $strGenders }}</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
