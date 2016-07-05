@extends("base")
@section('head')
    @parent
    <style>
        .table {
            table-layout: fixed;
            border-collapse: collapse;
        }
        .table-striped>tbody>tr>td .table-striped>tbody>tr>th {
            width: 13%;
        }
        .name {
            width: 9% !important;
        }
        .scroll {
            height: 200px;
            width: 100%;
            overflow-x:scroll;
        }
    </style>
@endsection
@section("content")
    <h1>match</h1>
    @foreach($match as $party)
        <table class="table table-striped">
            <thead>
            <tr>
                <th>NAME</th>
                <th>CHAMPION</th>
            </tr>
            </thead>
            <tbody>
            @foreach($party as $summoner => $champ)
                <tr>
                    <td><a href="/stats/{{ $summoner }}">{{ $summoners[$summoner] }}</a></td>
                    <td><a href="#champion_{{ $champions[$champ]->id }}">{{ $champions[$champ]->name }}</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endforeach

    <h1>champion</h1>
    <table class="table table-striped">
        <thead>
        <tr>
            <th class="name">champ</th>
            <th>allytips</th>
            <th>enemytips</th>
            <th>passive</th>
            <th>Qspell</th>
            <th>Wspell</th>
            <th>Espell</th>
            <th>Rspell</th>
        </tr>
        </thead>
        <tbody>
        @foreach($champions as $champion)
            <tr id="champion_{{ $champion->id }}">
                <td class="name"><div class="scroll">{{ $champion->name }}</div></td>
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
