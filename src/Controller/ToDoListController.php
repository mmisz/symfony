<?php
/**
 * ToDoList controller.
 */

namespace App\Controller;

use App\Entity\ToDoList;
use App\Form\ListDeleteType;
use App\Form\ToDoType;
use App\Repository\ListStatusRepository;
use App\Repository\ToDoListRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ToDoList.
 *
 * @Route("/to-do")
 */
class ToDoListController extends AbstractController
{
    /**
     * Index controller.
     *
     * @param Request $request
     * @param ToDoListRepository $toDoListRepository
     * @param PaginatorInterface $paginator
     * @return Response
     *
     * @Route(
     *     "/",
     *     name="to_do_index",
     * )
     */
    public function index(Request $request, ToDoListRepository $toDoListRepository, PaginatorInterface $paginator): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $pagination = $paginator->paginate(
                $toDoListRepository->queryAll(),
                $request->query->getInt('page', 1),
                $toDoListRepository::PAGINATOR_ITEMS_PER_PAGE
            );
        } else {
            $pagination = $paginator->paginate(
                $toDoListRepository->queryByAuthor($this->getUser()),
                $request->query->getInt('page', 1),
                $toDoListRepository::PAGINATOR_ITEMS_PER_PAGE
            );
        }

        return $this->render(
            'to-do/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param ToDoList $toDoList
     * @param Request $request
     * @return Response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET", "POST"},
     *     name="to_do_show",
     *     requirements={"id": "[1-9]\d*"})
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('VIEW', toDoList)")
     */
    public function show(ToDoList $toDoList, Request $request): Response
    {
        /* if ($toDoList->getAuthor() !== $this->getUser()) {
             $this->addFlash('warning', 'message.item_not_found');

             return $this->redirectToRoute('to_do_index');
         }*/
        return $this->render(
            'to-do/show.html.twig',
            ['toDoList' => $toDoList]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request
     * @param ToDoList $toDoList
     * @param ToDoListRepository $toDoListRepository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/to-do-delete/{id}",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="to_do_delete",
     * )
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('DELETE', toDoList)")
     */
    public function delete(Request $request, ToDoList $toDoList, ToDoListRepository $toDoListRepository): Response
    {
        $form = $this->createForm(FormType::class, $toDoList, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if (!$form->isSubmitted() && $request->isMethod('DELETE')) {
            $form->submit($request->request->get($form->getName()));
        }
        if ($form->isSubmitted() && $form->isValid()) {
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
     * @param Request $request
     * @param ToDoList $toDoList
     * @param ToDoListRepository $toDoListRepository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/to-do-edit/{id}",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="to_do_edit",
     * )
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('EDIT', toDoList)")
     */
    public function edit(Request $request, ToDoList $toDoList, ToDoListRepository $toDoListRepository): Response
    {
        $form = $this->createForm(ToDoType::class, $toDoList, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ('done' == $toDoList->getStatus()->getName()) {
                $toDoList->setDoneDate(new \DateTime());
            } else {
                $toDoList->setDoneDate(null);
            }
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

    /**
     * Create action.
     *
     * @param Request $request
     * @param ToDoListRepository $toDoListRepository
     * @param ListStatusRepository $listStatusRepository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/to-do-create",
     *     methods={"GET", "POST"},
     *     name="to_do_create",
     * )
     */
    public function create(Request $request, ToDoListRepository $toDoListRepository, ListStatusRepository $listStatusRepository): Response
    {
        $toDoList = new ToDoList();
        $form = $this->createForm(ToDoType::class, $toDoList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $toDoList->setCreation(new \DateTime());
            $toDoList->setStatus($listStatusRepository->findOneBy(['name' => 'to do']));
            $toDoList->setAuthor($this->getUser());
            $toDoListRepository->save($toDoList);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('to_do_index');
        }

        return $this->render(
            'to-do/create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
