<?php

namespace App\Dto;

use Symfony\Component\HttpFoundation\Request;

class Dto
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->setRequest($request);
    }

    /**
     * @return Request
     */
    protected function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @param Request $request
     * @return PostCodeDto
     */
    public function setRequest(Request $request): PostCodeDto
    {
        $this->request = $request;
        return $this;
    }
}
