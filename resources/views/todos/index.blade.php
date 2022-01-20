@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xs">
                <a name="" id="" href="{{ route('todos.create') }}" class="btn btn-primary m-2" role="button">ajouter un todo</a>
            </div>
            @if (Route::currentRouteName()=='todos.index')
            <div class="col-xs">
                <a name="" id="" href="{{ route('todos.undone') }}" class="btn btn-warning m-2" role="button">voir les todos ouvertes</a>
            </div>
            <div class="col-xs">
                <a name="" id="" href="{{ route('todos.done') }}" class="btn btn-success m-2" role="button">voir les todos terminees</a>
            </div>
            @elseif(Route::currentRouteName()=='todos.done')
            <div class="col-xs">
                <a name="" id="" href="{{ route('todos.index') }}" class="btn btn-dark m-2" role="button">voir toutes les todos</a>
            </div>
            <div class="col-xs">
                <a name="" id="" href="{{ route('todos.undone') }}" class="btn btn-success m-2" role="button">voir les todos ouvertes</a>
            </div>
            @elseif(Route::currentRouteName()=='todos.undone')
            <div class="col-xs">
                <a name="" id="" href="{{ route('todos.index') }}" class="btn btn-dark m-2" role="button">voir toutes les todos</a>
            </div>
            <div class="col-xs">
                <a name="" id="" href="{{ route('todos.done') }}" class="btn btn-success m-2" role="button">voir les todos terminees</a>
            </div>
            @endif
        </div>
    </div>
    @foreach ($datas as $data)

        <div class="alert alert-{{ $data->done ? 'success' : 'warning' }}" role="alert">
            <div class="row">
                <div class="col-sm">
                    <p class="my-0">
                     <strong>
                        <span class="badge badge-dark">
                                    #{{ $data->id }}
                        </span>
                     </strong>
                     <small>
                         créée {{ $data->created_at->from() }} par
                         {{ Auth::user()->id==$data->user->id ? 'moi': $data->user->name }}
                         @if ($data->todoAffectedTo && $data->todoAffectedTo->id==Auth::user()->id)
                            affectée a moi
                         @elseif ($data->todoAffectedTo)
                            {{ $data->todoAffectedTo ? ', affectee a '.$data->todoAffectedTo->name: '' }}
                         @endif

                         {{--  --}}
                         @if ($data->todoAffectedTo && $data->todoAffectedBy && $data->todoAffectedBy->id==Auth::user()->id)
                            par moi meme
                         @elseif($data->todoAffectedTo && $data->todoAffectedBy && $data->todoAffectedBy->id!=Auth::user()->id)

                             par {{ $data->todoAffectedBy->name }}
                         @endif

                     </small>
                     @if ($data->done)
                        <small>
                            <p>
                                Terminée
                                {{ $data->updated_at->from() }} -Terminee en
                                {{ $data->updated_at->diffForHumans($data->created_at,1) }}
                            </p>
                        </small>

                     @endif
                    </p>
                    <details>
                        <summary>
                            <strong> {{ $data->name }}@if($data->done) <span class="badge badge-success">done</span>@endif</strong>
                        </summary>
                        <p>{{ $data->description }}</p>
                    </details>

                </div>
                {{-- button affecter --}}

                {{-- done/undone --}}
                <div class="col-sm form-inline justify-content-end my-1">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                    Affecter a
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @foreach ($users as $user)
                                <a class="dopdown-item" href="/todos/{{ $data->id}}/affectedto/{{ $user->id }}" style="text-decoration: none">&nbsp;&nbsp;{{ $user->name }}</a><br>
                            @endforeach
                        </div>
                    </div>
                    @if ($data->done==0)
                        <form action="{{ route('todos.makedone',$data->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success mx-1" style="min-width: 90px">Done</button>
                        </form>
                    @else
                        <form action="{{ route('todos.makeundone',$data->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-warning mx-1" style="min-width: 90px">Undone</button>
                        </form>
                    @endif
                    {{-- button editer --}}
                    @can('edit', $data)
                        <a name="" id="" class="btn btn-info mx-1" href="{{ route('todos.edit',$data->id) }}" role="button">Editer</a>
                    @elsecannot('edit', $data)
                        <a name="" id="" class="btn btn-info mx-1 disabled" href="{{ route('todos.edit',$data->id) }}" role="button">Editer</a>
                    @endcan
                    {{-- Effacer --}}
                    @can('delete', $data)
                    <form action="{{ route('todos.destroy',$data->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger mx-1">Effacer</button>
                    </form>
                    @elsecannot('delete', $data)
                        <form action="{{ route('todos.destroy',$data->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mx-1 " disabled>Effacer</button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>

    @endforeach
    {{ $datas->links() /*pour la pagination des pages*/  }}
@endsection

