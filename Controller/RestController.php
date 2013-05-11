<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\EncoderInterface;

class RestController
{
    protected $encoder;

    public function __construct(Request $request, EncoderInterface $encoder)
    {
        $this->request = $request;
        $this->encoder = $encoder;
    }

    public function indexAction($apiVersion = 'v1', $type = 'default', $name = 'default', $key=1)
    {
        $method = $this->request->getMethod();

        $response = array(
                'method'    =>  $method,
                'version'   =>  $apiVersion,
                'type'      =>  $type,
                'name'      =>  $name,
                'key'       =>  $key
            );

         return $this->createResponse($response, 200);
    }

    protected function createResponse($response, $statusCode)
    {
        return new Response(
            $this->encoder->encode($response),
            $statusCode,
            array('Content-Type', 'application/json')
        );
    }
}
