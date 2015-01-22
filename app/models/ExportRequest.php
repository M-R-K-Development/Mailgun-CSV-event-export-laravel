<?php


class ExportRequest extends Eloquent
{
    protected $fillable = ['name', 'event','processed', 'filename'];

    public $table = 'export_requests';

    public static $rules = [
        'name'  => 'required',
        'event' => 'in:,,accepted,rejected,delivered,failed,opened,clicked,unsubscribed,complained,stored',
    ];

    public static $eventTypes = ['accepted','rejected','delivered','failed','opened','clicked','unsubscribed','complained', 'stored'];
}
