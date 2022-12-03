@extends('main')

@section('title', 'Need-based Search')

@section('mainContent')
    <table class="table">
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Need Description</th>
            <th scope="col">Run Search</th>
        </tr>
        @foreach ($needmaps as $key => $needmap)
            <tr>
                <td>{{ $needmap->name }}</td>
                <td>{{ $needmap->description }}</td>
                <td> <button type="button" class="btn btn-primary" value="{{ $needmap->id }}"
                             onclick="getQuery(this.value)">O</button></td>
            </tr>
        @endforeach
    </table>

    <script>
        function getQuery(id) {
            $("#main-content").load("./ajax/needSearch", "needmap=" + id);
            document.title = "Search Results";
            return false;
        }
    </script>

@endsection
