<?php


class ExportRequest extends Eloquent
{
    protected $fillable = ['name', 'event','processed', 'filename'];

    public $table = 'export_requests';

    public static $rules = [
        'name' => 'required',
    ];

    public static $eventTypes = ['accepted','rejected','delivered','failed','opened','clicked','unsubscribed','complained', 'stored'];
}
