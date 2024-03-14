<style>
    .backGroundGray{
        background-color: gray;
    }
</style>
@php
    $name = '<h2>Nguyen Van A</h2>';
    
@endphp

{{ $name }}
<!-- echo htmlspecialchars($name) -->

{!! $name !!}
<!-- echo $name -->

Hello, {{ $name }}.
@php
     $scores = [3, 4, 6, 3, 1, 9, 10, 2, 8, 3 ,1, 7, 5];
     $scores = [];
@endphp

<table border="1">
    <tr>
        <th>STT</th>
        <th>Score</th>
        <th>Note</th>
    </tr>
    {{-- @foreach($scores as $score)
        <tr class="{{ $loop->even ? 'backGroundGray' : '' }}">
            <td>{{ $loop->iteration }}</td>
            <td>{{ $score }}</td>
            <td>{{ $score > 5 ? 'Good' : 'Bad' }}</td>
        </tr>
    @endforeach --}}
    @forelse ($scores as $score)
        <tr class="{{ $loop->even ? 'backGroundGray' : '' }}">
            <td>{{ $loop->iteration }}</td>
            <td>{{ $score }}</td>
            <td>{{ $score > 5 ? 'Good' : 'Bad' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="3">No Data</td>
        </tr>
    @endforelse
</table>