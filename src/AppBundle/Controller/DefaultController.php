<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Redmine\Client;

class DefaultController extends Controller
{
    const URL = 'https://redmine.ekreative.com';

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
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/admin", name="adm_test")
     */
    public function admAction()
    {
        $data = 'You have the FULL access to this page now! ';

        return $this->render('@App/index.html.twig', array(
            'data' => $data));
    }

    /**
     * @Route("/listProjects", name="listProjects")
     */
    public function viewAction()
    {
        $listProject = $this->connect()->project->listing();

        return $this->render('@App/listProjects.html.twig', ['listProject' => $listProject ]);

    }

    /**
     * @Route("/listIssues/{prId}", name="listIssues")
     */
    public function listIssuesAction($prId)
    {
        $listIssues = $this->connect()->issue->all(['project_id'=> $prId]);
        if (isset($listIssues['issues'])){
            $listIssuesArr= $listIssues['issues'];
        } else $listIssuesArr = 0;

        return $this->render('@App/listIssues.html.twig',['listIssues'=>$listIssuesArr]);
    }

    private function connect()
    {
        $user = $this->container->getParameter('app_redmine_user');
        $pass = $this->container->getParameter('app_redmine_pass');
        return $client = new Client(self::URL, $user, $pass);
    }
}
