<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Redmine\Client;

class TrackTimeController extends Controller
{
    const URL = 'https://redmine.ekreative.com';
    const API_KEY = '2fda745bb4cdd835fdf41ec1fab82a13ddc1a54c';
    const USER = 'phptest';
    const PASS = '9uu82T487m6V41G';

    /**
     * @Route("/time_list/{prId}", name="time_list")
     */
    public function indexAction($prId)
    {
        $client = new Client(self::URL, self::USER, self::PASS);// self::API_KEY);

        $list = $client->time_entry->all(['project_id' => $prId]);
        if (isset($list['time_entries'])) {
            $trackTime = $list['time_entries'];
        } else $trackTime = 0;

        return $this->render('@App/listTrackTime.html.twig', ['trackTime' => $trackTime]);
    }

    /**
     * @Route("/new_time_entries/{prId}", name="createTimeEntry")
     */
    public function newAction($prId)
    {
        $client = new Client(self::URL, self::USER, self::PASS);// self::API_KEY);


        return $this->render('@App/listTrackTime.html.twig', ['trackTime' => $trackTime]);
    }
}
