@extends('layouts.app')

@section('content')

<h2>検索結果</h2>

<h4>{{$weaPre->prefectJP}}<h4>

<p>{{$weaPre->prefectJP}}の天気は以下の通りです。</p>

<div>
   
</div>

<button id="test_jquery">ぽちっとな</button>

<script src="{{ mix('js/j_query.js') }}"></script>


@endsection