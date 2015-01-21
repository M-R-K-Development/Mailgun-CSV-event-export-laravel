@extends('layouts.main')


@section('content')

<section class="container">
    <h3 class="text-center">Request Listing</h3>
    <div class="btn-toolbar">
        <div class="btn-group">
            <a href="/create" class="btn btn-primary">New Request</a>
        </div>
    </div>

    <hr>

    @if($requests)
    <table class="table table-condensed">
        <thead>
            <tr>
                <th>Name</th>
                <th>Processed</th>
                <th>Event</th>
                <th>&nbsp;</th>
            </tr>
        </thead>

        <tbody>
            @foreach($requests as $request)
                <tr>
                    <td>{{ $request->name }}</td>
                    <td>
                        @if($request->processed)
                            Yes
                        @else
                            No
                        @endif
                    </td>

                    <td>{{ $request->event }}</td>
                    <td>
                        @if($request->processed)
                            <a href="/download/{{ $request->id }}?t={{ time() }}" class="btn btn-primary">Download</a>
                        @else
                            <a href="/show/{{ $request->id }}" class="btn btn-default">Start Export</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="row-fluid text-center">
        {{ $requests->links() }}
    </div>
    @endif
</section>


@stop
