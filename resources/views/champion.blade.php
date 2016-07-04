@extends("base")
@section('head')
    @parent
    <style>
        .scroll {
            height: 200px;
            overflow-x:scroll;
        }
    </style>
@endsection
@section("content")
<h1>チャンピオン一覧</h1>
<table class="table table-striped">
    <thead>
    <tr>
        <th>名前</th>
        <th>仲間時</th>
        <th>敵時</th>
        <th>パッシブ</th>
        <th>Q</th>
        <th>W</th>
        <th>E</th>
        <th>R</th>
    </tr>
    </thead>
    <tbody>
    @foreach($champions as $champion)
        <tr>
            <td><div class="scroll">{{ $champion->name }}</div></td>
            <td><div class="scroll">{{ $champion->allytips }}</div></td>
            <td><div class="scroll">{{ $champion->enemytips }}</div></td>
            <td><div class="scroll">{{ $champion->passive }}</div></td>
            <td><div class="scroll">{{ $champion->spells_q }}</div></td>
            <td><div class="scroll">{{ $champion->spells_w }}</div></td>
            <td><div class="scroll">{{ $champion->spells_e }}</div></td>
            <td><div class="scroll">{{ $champion->spells_r }}</div></td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
@section("footer")
@endsection
