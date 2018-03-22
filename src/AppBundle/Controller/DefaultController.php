<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Redmine\Client;

class DefaultController extends Controller
{
    const URL = 'https://redmine.ekreative.com';
    const API_KEY= '2fda745bb4cdd835fdf41ec1fab82a13ddc1a54c';
    const USER='phptest';
    const PASS='9uu82T487m6V41G';

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
    public function viewAction(Request $request)
    {
        $count=0;
        $listProject = 'API Data';
        $client = new Client(self::URL, self::USER, self::PASS);// self::API_KEY);

        $listProject = $client->project->listing();
        $listIssues = $client->issue->all();
        foreach ($listIssues['issues'] as $keyI=>$valueI) {
            foreach ($listProject as $keyP=>$valueP){
                if ($valueI['project']['id'] == $valueP) {
                    $listIssuesArr[$valueI['id']] = $valueP; $count++; }
            }
        }
        $dataAPI= $listIssues['issues'];

        return $this->render('@App/testAPI.html.twig', [
            'listProject' => $listProject, 'listIssues' => $listIssues, 'dataAPI'=> $dataAPI,
            'listIA'=>$listIssuesArr, 'count'=>$count]);

    }
}
