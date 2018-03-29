<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 3/24/18
 * Time: 8:56 PM
 */

namespace AppBundle\Controller;

use AppBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Project;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class CommentsController
 * @Route("/comments")
 */
class CommentsController extends Controller
{
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
            $comments = $em->getRepository('AppBundle:Project')->findAllComments($prId);
            $deleteForms = $this->remEntity($comments);
        }

        return $this->render('AppBundle::listComments.html.twig', [
            'project' => $project, 'comments' => $comments, 'deleteForms' => $deleteForms]);
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

        $form = $this->createForm(new CommentType(), $comment)
            ->add('add', SubmitType::class);

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
     * @Route("/edit/{id}", name="edit_comment", requirements={"id": "\d+"})
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Comment')->find($id);

        $form = $this->createForm(new CommentType(), $entity)
            ->add('save', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('comm_list', ['prId' => $entity->getProject()->getIdPr(),
                'prName' => $entity->getProject()->getProjectName()]);
        }

        return $this->render('AppBundle::editComment.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/rem/{id}", name="remove_comment", requirements={"id": "\d+"})
     * @Method("DELETE")
     */
    public function remAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Comment')->find($id);
        $idPr = $entity->getProject()->getIdPr();
        $prName = $entity->getProject()->getProjectName();
        $em->remove($entity);
        $em->flush();

        return $this->redirectToRoute('comm_list', ['prId' => $idPr, 'prName' => $prName]);
    }

    private function remEntity($entity_list)
    {
        $deleteForms = [];
        foreach ($entity_list as $entity) {
            $deleteForms[$entity->getId()] = $this->createFormBuilder($entity)
                ->setAction($this->generateUrl('remove_comment', ['id' => $entity->getId()]))
                ->setMethod('DELETE')
                ->add('submit', SubmitType::class, [
                    'label' => 'del ',
                    'attr' => ['class' => 'btn btn-sm btn-outline-dark']
                ])
                ->getForm()->createView();
        }
        return $deleteForms;
    }
}
