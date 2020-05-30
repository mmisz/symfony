<?php
/**
 * ToDoList controller.
 */

namespace App\Controller;

use App\Entity\ListElement;
use App\Entity\ToDoList;
use App\Form\ToDoType;
use App\Repository\ListElementRepository;
use App\Repository\ToDoListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\ListDeleteType;

/**
 * Class ToDoList.
 *
 * @Route("/to-do")
 */
class ToDoListController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
     * @param \App\Repository\ToDoListRepository            $toDoList ToDoList repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator      Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="to_do_index",
     * )
     */
    public function index(Request $request, ToDoListRepository $toDoList, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $toDoList->queryAll(),
            $request->query->getInt('page', 1),
            ToDoListRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'to-do/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\ToDoList $toDoList ToDoList entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET", "POST"},
     *     name="to_do_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(ToDoList $toDoList, Request $request): Response
    {

        return $this->render(
            'to-do/show.html.twig',
            [
            'toDoList' => $toDoList]
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
     *     "/list-delete/{id}",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="to_do_delete",
     * )
     */
    public function delete(Request $request, ToDoList $toDoList, ToDoListRepository $toDoListRepository): Response
    {
        $form = $this->createForm(ListDeleteType::class, $toDoList, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if (!$form->isSubmitted() && $request->isMethod('DELETE')) {
            $form->submit($request->request->get($form->getName()));
        }
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', 'message_deleted_successfully');
            $toDoListRepository->delete($toDoList);
            return $this->redirectToRoute('to_do_index');
        }
        return $this->render(
            'to-do/delete.html.twig',
            [
                'form' => $form->createView(),
                'toDoList' => $toDoList,
                'listId' => $toDoList->getId(),
            ]
        );
    }
    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Entity\ListCategory                      $category           Category entity
     * @param \App\Repository\ListCategoryRepository        $categoryRepository Category repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="to_do_edit",
     * )
     */
    public function edit(Request $request, ToDoList $toDoList, ToDoListRepository $toDoListRepository): Response
    {
        $form = $this->createForm(ToDoType::class, $toDoList, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $toDoListRepository->save($toDoList);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('to_do_index');
        }

        return $this->render(
            'to-do/edit.html.twig',
            [
                'form' => $form->createView(),
                'toDoList' => $toDoList,
            ]
        );
    }
}
