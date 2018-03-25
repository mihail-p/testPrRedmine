<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 3/24/18
 * Time: 8:56 PM
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Project;
use Redmine\Client;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class CommentsController
 * @Route("/comments")
 */
class CommentsController extends Controller
{
    const URL = 'https://redmine.ekreative.com';

    /**
     * @Route("/{prId}/{prName}", name="comm_list", requirements={"prId": "\d+"})
     */
    public function listAction($prId, $prName)
    {
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')
            ->findOneBy(['id_pr' => $prId]);
        if (!$project) {
            $new_project = new Project();
            $new_project->setIdPr($prId);
            $new_project->setProjectName($prName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($new_project);
            $em->flush();
            $project = $new_project;
        } else {
            $em = $this->getDoctrine()->getManager();
            $comments = $em->getRepository('AppBundle:Project')
                ->findAllComments($prId);
        }

        return $this->render('AppBundle::listComments.html.twig', [
            'project' => $project, 'comments' => $comments]);
    }

    /**
     * @Route("/new", name="new_comment")
     */
    public function newAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $comment = new Comment();

        $comment->setDate(new \DateTime('today'));
        $comment->setUserName($user);

        $form = $this->createFormBuilder($comment)
            ->add('user_name', TextType::class, ['disabled' => true])
            ->add('date', DateType::class, ['widget' => 'single_text', 'disabled' => true])
            ->add('comment', TextareaType::class, ['attr' => ['cols' => '70', 'rows' => '5']])
            ->add('project', EntityType::class, [
                'class' => 'AppBundle\Entity\Project',
                'property' => 'project_name',
                'attr' => [
                    'class' => 'chosen form-control', 'data-placeholder' => '-- choice Project --']
            ])
            ->add('add', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();


            return $this->redirectToRoute('comm_list', ['prId' => $comment->getProject()->getIdPr(),
                'prName' => $comment->getProject()->getProjectName()]);
        }

        return $this->render('AppBundle::newComment.html.twig', [
            'form' => $form->createView(), 'user' => $user]);
    }

    /**
     * @Route("/rem/{id}", name="remove_comment")
     */
    public function remAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Comment')->find($id);
        $idPr = $entity->getProject()->getIdPr();
        $prName = $entity->getProject()->getProjectName();
        $em->remove($entity);
        $em->flush();

        return $this->redirectToRoute('comm_list',['prId' => $idPr, 'prName' => $prName]);
    }

    private function connect()
    {
        $user = $this->container->getParameter('app_redmine_user');
        $pass = $this->container->getParameter('app_redmine_pass');

        return $client = new Client(self::URL, $user, $pass);
    }
}