@extends('layouts.app')

@section('content')

<div class="text-center py-5">

    <h1 class="display-4 fw-bold">
        LaravelBlog
    </h1>

    <p class="lead">
        Compartilhe conhecimento, publique artigos e interaja com outros desenvolvedores.
    </p>

    @guest

        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
            Começar Agora
        </a>

    @endguest

</div>

@endsection