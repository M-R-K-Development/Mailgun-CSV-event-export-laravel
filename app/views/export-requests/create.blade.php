@extends('layouts.main')


@section('content')

<section class="container">
    <div class="btn-toolbar">
        <div class="btn-group">
            <a href="/" class="btn btn-default">Listing</a>
        </div>
    </div>

    <hr>

    {{ Form::open(['route' => 'store']) }}
        <legend>New Request Form</legend>

        <div class="form-group">
            <label for="">Name</label>
            {{ Form::input('text', 'name', Input::old('name', null), ['class' => 'form-control']) }}

            {{  $errors->first('name') }}

        </div>

        <div class="form-group">
            <label for="">Events</label>
            {{ Form::select('event', $eventTypes, Input::old('event', ''),['class' => 'form-control']) }}
        </div>



        <button type="submit" class="btn btn-primary">Submit</button>
    {{ Form::close() }}


</section>


@stop
