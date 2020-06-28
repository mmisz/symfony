<?php
/**
 * ListCommentController controller.
 */

namespace App\Controller;

use App\Entity\ListComment;
use App\Entity\ToDoList;
use App\Form\ListCommentType;
use App\Repository\ListCommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ListComment.
 */
class ListCommentController extends AbstractController
{
    /**
     * Edit action.
     *
     * @param Request $request
     * @param ListComment $listComment
     * @param ListCommentRepository $listCommentRepository
     * @param TranslatorInterface $translator
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/list-comment-edit/{id}",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="list_comment_edit",
     * )
     */
    public function edit(Request $request, ListComment $listComment, ListCommentRepository $listCommentRepository, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(ListCommentType::class, $listComment, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $listCommentRepository->save($listComment);

            $this->addFlash('success', 'message_updated_successfully');
            $toDoList = $listComment->getToDoList();

            return $this->redirectToRoute('to_do_show', ['id' => $toDoList->getId()]);
        }

        return $this->render(
            'list-comment/edit.html.twig',
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
     * @param Request $request
     * @param ListComment $listComment
     * @param ListCommentRepository $listCommentRepository
     * @param TranslatorInterface $translator
     * @return Response
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
    public function delete(Request $request, ListComment $listComment, ListCommentRepository $listCommentRepository, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(ListCommentType::class, $listComment, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if (!$form->isSubmitted() && $request->isMethod('DELETE')) {
            $form->submit($request->request->get($form->getName()));
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'message_deleted_successfully');
            $listCommentRepository->delete($listComment);
            $toDoList = $listComment->getToDoList();

            return $this->redirectToRoute('to_do_show', ['id' => $toDoList->getId()]);
        }

        return $this->render(
            'list-comment/delete.html.twig',
            [
                'form' => $form->createView(),
                'listComment' => $listComment,
                'listId' => $listComment->getToDoList()->getId(),
            ]
        );
    }

    /**
     * Create action.
     *
     * @param ToDoList $toDoList
     * @param Request $request
     * @param ListCommentRepository $listCommentRepository
     * @param TranslatorInterface $translator
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/list-comment-create/{id}",
     *     methods={"GET", "POST"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="list_comment_create",
     * )
     */
    public function create(ToDoList $toDoList, Request $request, ListCommentRepository $listCommentRepository, TranslatorInterface $translator): Response
    {
        $listComment = new ListComment();
        $form = $this->createForm(ListCommentType::class, $listComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $listComment->setToDoList($toDoList);
            $this->addFlash('success', 'message_created_successfully');
            $listComment->setCreation(new \DateTime());
            $listCommentRepository->save($listComment);

            return $this->redirectToRoute('to_do_show', ['id' => $toDoList->getId()]);
        }

        return $this->render(
            'list-comment/create.html.twig',
            ['form' => $form->createView(),
                'listId' => $toDoList->getId(),
                ]
        );
    }
}
