<?php

use Carbon\Carbon;
use Illuminate\Console\Command;
use Mailgun\Mailgun;

class ExportMailgunEvents extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'app:export-mailgun-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export Mailgun events and stores to a csv file.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $apiKey = $_ENV['Mailgun.apiKey'];
        $domain = $_ENV['Mailgun.domain'];

        $uri     = "$domain/events";
        $mailgun = new Mailgun($apiKey);
        $limit   = 100; // max row from mailgun per call.
        $fetched = $limit;

        $filename = $domain . '-' . time() . '.csv';
        $dir      = storage_path('exports');

        if (!is_dir($dir)) {
            mkdir($dir);
        }

        $absolutePath = "$dir/$filename";

        $file = fopen($absolutePath, "w");

        $args = [];

        while ($fetched !== 0) {
            $response = $mailgun->get($uri, $args);
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

                $this->info(json_encode($row));

                fputcsv($file, $row);
            }
            $args     = [];
            $fetched  = count($events);
            $uri      = $response->http_response_body->paging->next;
            $this->info($uri);
            unset($events);
        }
        fclose($file);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
        );
    }
}
