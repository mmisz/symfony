<?php
/**
 * ToDoList controller.
 */

namespace App\Controller;

use App\Entity\ListElement;
use App\Entity\ToDoList;
use App\Repository\ListElementRepository;
use App\Repository\ToDoListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\ListCommentType;
use App\Repository\ListCommentRepository;
use App\Entity\ListComment;
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

}
