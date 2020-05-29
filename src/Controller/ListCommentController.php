<?php
/**
 * ListCommentController controller.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ListCommentType;
use App\Repository\ListCommentRepository;
use App\Entity\ListComment;
/**
 * Class ListComment.
 */
class ListCommentController extends AbstractController
{
    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Repository\ListComment $listComment
     * @param \App\Repository\ListCommentRepository $listCommentRepository
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route(
     *     "/list-comment-edit/{id}",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="list_comment_edit",
     * )
     */
    public function edit(Request $request, ListComment $listComment, ListCommentRepository $listCommentRepository): Response
    {
        $form = $this->createForm(ListCommentType::class, $listComment, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $listCommentRepository->save($listComment);

            $this->addFlash('success', 'message_added_successfully');
            $toDoList = $listComment->getToDoList();
            return $this->redirectToRoute('to_do_show',['id'=>$toDoList->getId()]);
        }

        return $this->render(
            'list-comment/editComment.html.twig',
            [
                'form' => $form->createView(),
                'listComment' => $listComment,
                'listId' => $listComment->getToDoList()->getId(),
            ]
        );
    }
    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Repository\ListComment $listComment
     * @param \App\Repository\ListCommentRepository $listCommentRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/list-comment-delete/{id}",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="list_comment_delete",
     * )
     */
    public function delete(Request $request, ListComment $listComment, ListCommentRepository $listCommentRepository): Response
    {
        $form = $this->createForm(ListCommentType::class, $listComment, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if (!$form->isSubmitted() && $request->isMethod('DELETE')) {
            $form->submit($request->request->get($form->getName()));
        }
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', 'message_deleted_successfully');
            $listCommentRepository->delete($listComment);
            $toDoList = $listComment->getToDoList();
            return $this->redirectToRoute('to_do_show',['id'=>$toDoList->getId()]);
        }
        return $this->render(
            'list-comment/deleteComment.html.twig',
            [
                'form' => $form->createView(),
                'listComment' => $listComment,
                'listId' => $listComment->getToDoList()->getId(),
            ]
        );
    }
}
