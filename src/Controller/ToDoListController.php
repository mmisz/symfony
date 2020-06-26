<?php
/**
 * ToDoList controller.
 */

namespace App\Controller;

use App\Entity\ToDoList;
use App\Form\ToDoType;
use App\Repository\ListStatusRepository;
use App\Repository\ToDoListRepository;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\ListDeleteType;
use Symfony\Contracts\Translation\TranslatorInterface;

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
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param ToDoListRepository $toDoListRepository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="to_do_index",
     * )
     */
    public function index(Request $request, ToDoListRepository $toDoListRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $toDoListRepository->queryAll(),
            $request->query->getInt('page', 1),
            ToDoListRepository::PAGINATOR_ITEMS_PER_PAGE
        );
        return $this->render(
            'to-do/index.html.twig',
            ['pagination' => $pagination,]
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
     *     requirements={"id": "[1-9]\d*"})
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('VIEW', toDoList)")
     *
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
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param ToDoList $toDoList
     * @param ToDoListRepository $toDoListRepository
     * @param TranslatorInterface $translator
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route(
     *     "/to-do-delete/{id}",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="to_do_delete",
     * )
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('DELETE', toDoList)")
     */
    public function delete(Request $request, ToDoList $toDoList, ToDoListRepository $toDoListRepository, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(ListDeleteType::class, $toDoList, ['method' => 'DELETE']);
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
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\ListCategory $category Category entity
     * @param \App\Repository\ListCategoryRepository $categoryRepository Category repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws Exception
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
    public function edit(Request $request, ToDoList $toDoList, ToDoListRepository $toDoListRepository, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(ToDoType::class, $toDoList, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($toDoList->getStatus()->getName()=='done') {
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
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Entity\ToDoList                     $toDoList           ToDoList entity
     * @param \App\Repository\ToDoListRepository        $toDoListRepository ToDoList repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/to-do-create",
     *     methods={"GET", "POST"},
     *     name="to_do_create",
     * )
     *
     */
    public function create(Request $request, ToDoListRepository $toDoListRepository, ListStatusRepository $listStatusRepository, TranslatorInterface $translator): Response
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
