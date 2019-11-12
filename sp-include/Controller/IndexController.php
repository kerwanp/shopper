<?php

namespace Shopper\Controller;

use eftec\bladeone\BladeOne;
use Shopper\Service\TestService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController
{

    private $bladeOne;

    public function __construct(BladeOne $bladeOne)
    {
    }

    /**
     * @Route(path="/")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        return new Response("TEST");
    }

}