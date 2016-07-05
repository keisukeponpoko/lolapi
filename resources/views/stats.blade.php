@extends("base")
@section('head')
    @parent
@endsection
@section("content")
    <h1>{{ $summoners[$id] }}</h1>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>CHAMPION</th>
            <th>TOTAL</th>
            <th>WIN</th>
            <th>LOSE</th>
            <th>KILL</th>
            <th>DEATH</th>
            <th>ASSIST</th>
            <th>CS</th>
            <th>TOP</th>
            <th>MID</th>
            <th>BOTTOM</th>
            <th>JUNGLE</th>
        </tr>
        </thead>
        <tbody>
        @foreach($stats as $championId => $value)
            <tr>
                <td>{{ $champions[$value['CHAMP']] }}</td>
                <td>{{ $value['TOTAL'] }}</td>
                <td>{{ $value['WIN'] }}</td>
                <td>{{ $value['LOSE'] }}</td>
                <td>{{ $value['KILL'] }}</td>
                <td>{{ $value['DEATH'] }}</td>
                <td>{{ $value['ASSIST'] }}</td>
                <td>{{ $value['CS'] }}</td>
                <td>{{ $value['TOP'] }}</td>
                <td>{{ $value['MID'] }}</td>
                <td>{{ $value['BOTTOM'] }}</td>
                <td>{{ $value['JUNGLE'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
@section("footer")
@endsection
