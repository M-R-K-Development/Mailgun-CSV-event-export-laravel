<?php
use Illuminate\Routing\Controller;

class ExportRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /exportrequest
     *
     * @return Response
     */
    public function index()
    {
        $requests = ExportRequest::paginate(10);

        return View::make('export-requests.index')
                    ->with('requests', $requests);
    }

    /**
     * Show the form for creating a new resource.
     * GET /exportrequest/create
     *
     * @return Response
     */
    public function create()
    {
        $eventTypes = [' ' => 'All'] + array_combine(ExportRequest::$eventTypes, ExportRequest::$eventTypes);

        return View::make('export-requests.create')
            ->with('eventTypes', $eventTypes);
    }

    /**
     * Store a newly created resource in storage.
     * POST /exportrequest
     *
     * @return Response
     */
    public function store()
    {
        $repo = new ExportRequestRepository;

        try {
            $request = $repo->store(Input::all());
        } catch (Exception $e) {
            return Redirect::to('/create')
                    ->withErrors($repo->validator);
        }

        return Redirect::route('export-requests.show', array('id' => $request->id));
    }

    /**
     * Display the specified resource.
     * GET /exportrequest/{id}
     *
     * @param  int      $id
     * @return Response
     */
    public function show($id)
    {
        $request = ExportRequest::find($id);
        if (!$request) {
            Redirect::to('/');
        }

        return View::make('export-requests.show', ['request' => $request]);
    }

    /**
     * Show the form for editing the specified resource.
     * GET /exportrequest/{id}/edit
     *
     * @param  int      $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * PUT /exportrequest/{id}
     *
     * @param  int      $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /exportrequest/{id}
     *
     * @param  int      $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * [export description]
     *
     * @param [type] $id [description]
     *
     * @return [type] [description]
     */
    public function export($id)
    {
        $request = ExportRequest::find($id);

        if (!$request) {
            Redirect::to('/');
        }

        if ($request->processed) {
            return Response::json(['processed' => true, 'reload' => false]);
        } else {
            $filename = Input::get('filename', $_ENV['Mailgun.domain'] . '-' . time() . '.csv');

            $defaultUri = $_ENV['Mailgun.domain'] . '/events';
            $event      = trim($request->event);
            if ($event) {
                $defaultUri .= "?event=$event";
            }
            $uri = Input::get('uri', $defaultUri);

            $helper                       = new ExportHelper;
            list($response, $count, $uri) = $helper->fetchAndExport($uri, storage_path('exports/'.$filename));

            if ($count === 0) {
                $request->filename  = $filename;
                $request->processed = 1;
                $request->save();

                return Response::json(['processed' => true, 'reload' => true]);
            } else {
                return Response::json(['processed' => false, 'uri' => $uri, 'filename' => $filename]);
            }
        }
    }

    /**
     * [download description]
     *
     * @param [type] $id [description]
     *
     * @return [type] [description]
     */
    public function download($id)
    {
        $request = ExportRequest::find($id);

        if (!$request || !$request->processed) {
            Redirect::to('/');
        }

        $path = storage_path('exports/' . $request->filename);

        return Response::download($path);
    }
}
