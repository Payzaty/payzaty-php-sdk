<?php

namespace Payzaty\Response;

use RuntimeException;

class StatusResponse
{
    public $success;
    public $paid;
    public $status;
    public $udf1;
    public $udf2;
    public $udf3;
    public $error;
    public $errorText;

    public function __construct($data)
    {
        $this->success = (bool)$data->success;
        $this->paid = (bool)$data->paid ?? false;
        $this->status = $data->status ?? '';
        $this->udf1 = $data->udf1 ?? '';
        $this->udf2 = $data->udf2 ?? '';
        $this->udf3 = $data->udf3 ?? '';
        $this->error = $data->error ?? '';
        $this->errorText = $data->errorText ?? '';
    }
}
