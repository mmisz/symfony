<?php
/**
 * ListCategory controller.
 */

namespace App\Controller;

use App\Entity\ListCategory;
use App\Form\ListCategoryType;
use App\Repository\ListCategoryRepository;
use App\Repository\ToDoListRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ListCategoryController.
 *
 * @Route("/list-category")
 */
class ListCategoryController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request                HTTP request
     * @param \App\Repository\ListCategoryRepository    $listCategoryRepository ListCategory repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator              Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="list_category_index",
     * )
     */
    public function index(Request $request, ListCategoryRepository $listCategoryRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $listCategoryRepository->queryAll(),
            $request->query->getInt('page', 1),
            ListCategoryRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'list-category/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param ListCategory $category
     * @param ToDoListRepository $toDoListRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     *
     * @Route(
     *     "/{id}/list-category",
     *     methods={"GET"},
     *     name="list_category_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(ListCategory $category, ToDoListRepository $toDoListRepository, PaginatorInterface $paginator, Request $request): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $pagination = $paginator->paginate(
                $toDoListRepository->findBy(['category' => $category]),
                $request->query->getInt('page', 1),
                ToDoListRepository::PAGINATOR_ITEMS_PER_PAGE
            );
        } else {
            $pagination = $paginator->paginate(
                $toDoListRepository->queryByAuthorAndCategory($this->getUser(), $category),
                $request->query->getInt('page', 1),
                ToDoListRepository::PAGINATOR_ITEMS_PER_PAGE
            );
        }

        return $this->render(
            'list-category/show.html.twig',
            [
                'pagination' => $pagination,
                'category' => $category,
            ]
        );
    }

    /**
     * Create action
     *
     * @param Request $request
     * @param ListCategoryRepository $categoryRepository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/list-category-create",
     *     methods={"GET", "POST"},
     *     name="list_category_create",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(Request $request, ListCategoryRepository $categoryRepository): Response
    {
        $category = new ListCategory();
        $form = $this->createForm(ListCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->save($category);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('list_category_index');
        }

        return $this->render(
            'list-category/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request
     * @param ListCategory $category
     * @param ListCategoryRepository $categoryRepository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/list-category-edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="list_category_edit",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, ListCategory $category, ListCategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(ListCategoryType::class, $category, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->save($category);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('list_category_index');
        }

        return $this->render(
            'list-category/edit.html.twig',
            [
                'form' => $form->createView(),
                'category' => $category,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request
     * @param ListCategory $category
     * @param ListCategoryRepository $categoryRepository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/list-category-delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="list_category_delete",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, ListCategory $category, ListCategoryRepository $categoryRepository): Response
    {
        if ($category->getToDoLists()->count()) {
            $this->addFlash('warning', 'message_category_contains_tasks');

            return $this->redirectToRoute('list_category_index');
        }
        $form = $this->createForm(FormType::class, $category, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->delete($category);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('list_category_index');
        }

        return $this->render(
            'list-category/delete.html.twig',
            [
                'form' => $form->createView(),
                'category' => $category,
            ]
        );
    }
}
