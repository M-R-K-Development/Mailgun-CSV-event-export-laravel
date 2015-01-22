<?php

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
        $request = ExportRequest::where('processed', '=', 0)
                            ->first();

        if (!$request) {
            $this->info('No request found');

            return;
        }

        $this->info("Processing request $request->name");

        $filename = $_ENV['Mailgun.domain'] . '-' . time() . '.csv';

        $uri        = $_ENV['Mailgun.domain'] . '/events';
        $event      = trim($request->event);
        if ($event) {
            $uri .= "?event=$event";
        }

        $count  = 100;
        $helper = new ExportHelper;

        while ($count == 100) {
            $this->info("Processing $uri");
            list($response, $count, $uri) = $helper->fetchAndExport($uri, storage_path('exports/'.$filename));
        }

        $request->filename  = $filename;
        $request->processed = 1;
        $request->save();

        $this->info("file saved to " . storage_path('exports/' . $filename));
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
