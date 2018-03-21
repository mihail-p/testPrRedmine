<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Redmine\Client;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $data = 'some data';

        return $this->render('@App/index.html.twig', array(
            'data' => $data));
    }
    /**
     * @Route("/testAPI", name="test_api")
     */
    public function test(Request $request)
    {
        $dataAPI='API Data';
        $client = new Client('https://redmine.ekreative.com', '2fda745bb4cdd835fdf41ec1fab82a13ddc1a54c');
        $client->project->all(['limit' => 10]);

        $dataAPI = $client;

        return $this->render('@App/testAPI.html.twig',['dataAPI'=> $dataAPI]);

    }
}
