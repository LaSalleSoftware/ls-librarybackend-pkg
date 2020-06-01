@extends('lasallesoftwarelibrary::basic.layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Logout') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary" dusk="logout-button">
                                        {{ __('Logout') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>



@endsection
