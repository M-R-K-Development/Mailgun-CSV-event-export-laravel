@extends('layouts.main')


@section('content')

<section class="container">
    <h3 class="text-center">Request - {{ $request->name }}</h3>
    <div class="btn-toolbar">
        <div class="btn-group">
            <a href="/" class="btn btn-default">Listing</a>
        </div>
    </div>

    <hr>

    @if($request->processed)
        <a href="/download/{{ $request->id }}?t={{ time() }}" class="btn btn-large btn-block btn-primary">Download</a>
    @else

        <ul id="log"></ul>

    @endif
</section>


@stop


@section('scripts')

@if(!$request->processed)

    <script>
        var calls = 1;
        function initiateExport(uri, filename){
            var exportUri = "/{{ $request->id }}/export?t=" + new Date().getTime();
            var payload = {};

            if(uri != null){
                payload.uri = uri;
                payload.filename = filename;
            }

            log('Sending request to process batch ' + calls)

            jQuery.getJSON( exportUri, payload, function(response, textStatus) {
                log('Processed batch ' + calls);
                calls++;

                if(response.processed){
                    log('Export request processed. Reloading page...');
                    window.location.reload(true);
                } else {
                    initiateExport(response.uri, response.filename);
                }

            });



        }

        function log(message){
            $('#log').append('<li>' + message + "</li>");
        }


        jQuery(document).ready(function($) {
            log('Initialising....');
            initiateExport(null, null);
        });

    </script>


@endif


@stop
