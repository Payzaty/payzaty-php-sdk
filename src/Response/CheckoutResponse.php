<?php

namespace Payzaty\Response;

use RuntimeException;

class CheckoutResponse
{
    public $success;
    public $checkoutId;
    public $checkoutUrl;
    public $error;
    public $errorText;

    public function __construct($data)
    {
        $this->success = $data->success;
        $this->checkoutId = $data->checkoutId ?? '';
        $this->checkoutUrl = $data->checkoutUrl ?? '';
        $this->error = $data->error ?? '';
        $this->errorText = $data->errorText ?? '';
    }

}
