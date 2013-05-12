<?php

namespace ADR\Bundle\Symfony2ErlangBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ADR\Bundle\Symfony2ErlangBundle\Service\Encoder\EncoderInterface;
use ADR\Bundle\Symfony2ErlangBundle\Service\Rest\RestHandlerInterface;

class RestController
{
    protected $request;
    protected $encoder;
    protected $restHandler;

    public function __construct(Request $request, EncoderInterface $encoder, RestHandlerInterface $restHandler)
    {
        $this->request = $request;
        $this->encoder = $encoder;
        $this->restHandler = $restHandler;
    }

    public function indexAction($apiVersion = 'v1', $type = 'default', $name = 'default', $key=1)
    {
        $request = array(
                'method'    =>  $this->request->getMethod(),
                'version'   =>  $apiVersion,
                'type'      =>  $type,
                'name'      =>  $name,
                'key'       =>  $key
            );
        var_dump(get_class($this->restHandler));
        $response = $this->restHandler->handle($request);

        return $this->createResponse($response);
    }

    protected function createResponse(array $response)
    {
        $statusCode = $response['status_code'];
        unset($response['status_code']);

        if (isset($response['test_data']) && PHP_SAPI !== 'cli') {
            unset($response['test_data']);
        }

        return new Response(
            $this->encoder->encode($response),
            $statusCode,
            array('Content-Type', 'application/json')
        );
    }

    /**
     * Used only Test Environment
     *
     * @param RestHandlerInterface $restHandler
     */
    public function setRestHandler(RestHandlerInterface $restHandler)
    {
        $this->restHandler = $restHandler;
    }
}
