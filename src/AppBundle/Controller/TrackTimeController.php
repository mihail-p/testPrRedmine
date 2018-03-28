<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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

    /**
     * @Route("/time_list/{prId}", name="time_list", requirements={"prId": "\d+"})
     */
    public function indexAction($prId)
    {
        $list = $this->connect()->time_entry->all(['project_id' => $prId]);
        if (isset($list['time_entries'])) {
            $trackTime = $list['time_entries'];
            $remTrackTimeList = $this->remEntity($trackTime);
        } else {
            $trackTime = 0;
            $remTrackTimeList = 0;
        }

        return $this->render('@App/listTrackTime.html.twig', [
            'trackTime' => $trackTime, 'remTrackTimeList' => $remTrackTimeList]);
    }

    /**
     * @Route("/new_time_entries/{prId}", name="create_time_entry", requirements={"prId": "\d+"})
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
            $this->connect()->time_entry->create([
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

            return $this->redirectToRoute('time_list', ['prId' => $prId]);
        }

        return $this->render('@App/addTrackTime.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/rem_time_entries/{prId}/{teId}", name="rem_time_entry", requirements={"prId": "\d+"})
     * @Method("DELETE")
     */
    public function removeAction($prId, $teId)
    {
        $this->connect()->time_entry->remove($teId);

        return $this->redirectToRoute('time_list', ['prId' => $prId]);
    }

    private function connect()
    {
        $url = $this->container->getParameter('app_redmine_url');
        $pass = $this->container->getParameter('app_redmine_pass');
        $user = $this->container->getParameter('app_redmine_user');
        return $client = new Client($url, $user, $pass);
    }

    private function remEntity($entity_list)
    {
        $deleteForms = [];
        foreach ($entity_list as $entity) {
            $deleteForms[$entity['id']] = $this->createFormBuilder()
                ->setAction($this->generateUrl('rem_time_entry', array(
                    'prId' => $entity['project']['id'], 'teId' => $entity['id'])))
                ->setMethod('DELETE')
                ->add('submit', SubmitType::class, [
                    'label' => 'del ',
                    'attr' => ['class' => 'btn btn-sm btn-light']
                ])
                ->getForm()->createView();
        }
        return $deleteForms;
    }
}
