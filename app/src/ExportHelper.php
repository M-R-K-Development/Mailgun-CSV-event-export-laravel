<?php

use Carbon\Carbon;
use Mailgun\Mailgun;

class ExportHelper
{
    /**
     * [fetchAndExport description]
     *
     * @param [type] $uri          [description]
     * @param [type] $absolutePath [description]
     *
     * @return [type] [description]
     */
    public function fetchAndExport($uri, $absolutePath)
    {
        $dir = pathinfo($absolutePath, PATHINFO_DIRNAME);

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $file = fopen($absolutePath, "a");

        $apiKey = $_ENV['Mailgun.apiKey'];
        $domain = $_ENV['Mailgun.domain'];

        $mailgun  = new Mailgun($apiKey);
        $response = $mailgun->get($uri);
        $events   = $response->http_response_body->items;

        foreach ($events as $event) {
            $row     = [];

            $createdOn = Carbon::createFromTimestampUTC((int)$event->timestamp);
            $row[]     = $createdOn->toDateTimeString();

            $row[] = $event->event;

            $headers = (array) $event->message->headers;
            $parts   =  explode('@', $headers['message-id']);
            $row[]   = $parts[0];

            $row[] = $event->recipient;

            fputcsv($file, $row);
        }

        fclose($file);

        $parts = explode('/events/', $response->http_response_body->paging->next, 2);

        return array($response, count($events), "$domain/events/" . $parts[1]);
    }
}
