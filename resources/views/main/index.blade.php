@extends('layouts.app')

@section('content')
    <h2>自由検索</h2>
    
        <p>
            カテゴリーと行き先を指定することで<br>
            その場所の今後5日間の天気とそのカテゴリーの人気TOP10のモノをご案内します。
        </p>
    <div class="row">
        <div>
            {{ Form::open(['action' => 'JuppiterController@simpleResult']) }}
                <div class="form-group">
                    {{ Form::label('prefecture', '都道府県:') }}
                    {{ Form::select('prefecture', $prefect_form,['class' => 'form-control']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('categories','カテゴリー:') }}
                    {{ Form::select('categories',$category_form, ['class' => 'form-control']) }}
                </div>
                {{ Form::submit('検索', ['class' => 'btn btn-primary']) }}
            {{ Form::close() }}
        </div>
    </div>
    <div class="row">
        <div>
            
        </div>
    <div>
    <div>
        <div>
        </div>
    <div>
    <div>
        <div>
        </div>
    <div>
    <div>
        <div>
        </div>
    <div>

@endsection