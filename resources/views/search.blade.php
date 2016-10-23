@extends("base")
@section('head')
    @parent
@endsection
@section("content")
    <h1>Summoner Search</h1>
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">Summoner Name</div>
            <div class="panel-body">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        {{ $errors->all()[0] }}
                    </div>
                @endif

                <form class="form-horizontal" role="form" method="POST" action="/search">
                    {{-- CSRF対策--}}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        search
                    </button>
                </form>
            </div><!-- .panel-body -->
        </div><!-- .panel -->
    </div><!-- .container-fluid -->
@endsection
@section("footer")
@endsection
