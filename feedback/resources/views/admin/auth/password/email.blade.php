@extends('admin.layouts.auth-layout')

@section('auth-content')

    <div class="container">
        <div class="row text-center">
            <div class="col-12 col-md-6 offset-md-3">

                <h3>Type You Email To Reset Your Password</h3>
                <form class="form-horizontal" role="form" method="POST" action="{{ route('admin.password.email') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">


                        <div class="col-12">
                            <input id="email" type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}" required>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                Send Password Reset Link
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection