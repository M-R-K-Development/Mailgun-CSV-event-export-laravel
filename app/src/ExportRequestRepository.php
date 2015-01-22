<?php

class ExportRequestRepository
{
    public $validator;

    /**
     * [store description]
     *
     * @param [type] $input [description]
     *
     * @return [type] [description]
     */
    public function store($input)
    {
        $this->validator = Validator::make($input, ExportRequest::$rules);

        if ($this->validator->passes()) {
            return ExportRequest::create($input);
        } else {
            throw new Exception("Validation Failed", 1);
        }
    }
}
