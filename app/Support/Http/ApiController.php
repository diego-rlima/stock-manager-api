<?php

namespace App\Support\Http;

class ApiController extends Controller
{
    /**
     * API response helper.
     *
     * @var \App\Support\Http\Response
     */
    protected Response $response;

    /**
     * API parameters helper.
     *
     * @var \App\Support\Http\Parameters
     */
    protected Parameters $parameters;

    /**
     * Creates a new class instance.
     *
     * @param Response   $response
     * @param Parameters $parameters
     */
    public function __construct(Response $response, Parameters $parameters)
    {
        $this->response = $response;
        $this->parameters = $parameters;
    }
}
