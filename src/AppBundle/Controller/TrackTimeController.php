<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\TrackTime;
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
    public function newAction(Request $request, $prId)
    {
        $trackTime = new TrackTime();
        $trackTime->setDate(date("Y-m-d")); /* new \DateTime('today')); */

        $form = $this->createFormBuilder($trackTime)
            ->add('date', TextType::class)//DateType::class, ['input' => 'string'])
            ->add('hours', IntegerType::class)
            ->add('comment', TextType::class)
            ->add('Activity', ChoiceType::class, ['choices' => [
                '8' => 'Design',
                '9' => 'Development',
                '10' => 'Management',
                '11' => 'Testing']])
            ->add('overtime', CheckboxType::class, ['required' => false])
            ->add('create', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $client = new Client(self::URL, self::USER, self::PASS);// self::API_KEY);
            $client->time_entry->create([
                'project_id' => $prId,
                'spent_on' => $trackTime->getDate(),
                'hours' => $trackTime->getHours(),
                'comments' => $trackTime->getComment(),
                'activity_id' => $trackTime->getActivity(),
                'custom_fields' => [
                    [
                        'id' => 5,
                        'name' => 'Overtime',
                    'value' => $trackTime->getOvertimeInt(),
                    ],
                ],
            ]);

            return $this->render('@App/showTrackTimeData.html.twig', ['timeDate' => $trackTime]);
        }

        return $this->render('@App/addTrackTime.html.twig', ['form' => $form->createView()]);
    }
}
