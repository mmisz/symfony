<?php
/**
 * NoteCategory controller.
 */

namespace App\Controller;

use App\Entity\NoteCategory;
use App\Form\NoteCategoryType;
use App\Repository\NoteCategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NoteCategory.
 *
 * @Route("/note-category")
 */
class NoteCategoryController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Repository\NoteCategoryRepository        $noteCategoryRepository NoteCategory repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator          Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="note_category_index",
     * )
     */
    public function index(Request $request, NoteCategoryRepository $categoryRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $categoryRepository->queryAll(),
            $request->query->getInt('page', 1),
            NoteCategoryRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'note-category/index.html.twig',
            ['pagination' => $pagination]
        );
    }
    /**
     * Show action.
     *
     * @param \App\Entity\NoteCategory $category NoteCategory entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}/note-category",
     *     methods={"GET"},
     *     name="note_category_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(NoteCategory $category): Response
    {
        return $this->render(
            'note-category/show.html.twig',
            ['category' => $category]
        );
    }
    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Repository\NoteCategoryRepository        $categoryRepository NoteCategory repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/note-category-create",
     *     methods={"GET", "POST"},
     *     name="note_category_create",
     * )
     */
    public function create(Request $request, NoteCategoryRepository $categoryRepository): Response
    {
        $category = new NoteCategory();
        $form = $this->createForm(NoteCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->save($category);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('note_category_index');
        }

        return $this->render(
            'note-category/create.html.twig',
            ['form' => $form->createView()]
        );
    }
    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Entity\NoteCategory                      $category           NoteCategory entity
     * @param \App\Repository\NoteCategoryRepository        $categoryRepository NoteCategory repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/note-category-edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="note_category_edit",
     * )
     */
    public function edit(Request $request, NoteCategory $category, NoteCategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(NoteCategoryType::class, $category, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->save($category);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('note_category_index');
        }

        return $this->render(
            'note-category/edit.html.twig',
            [
                'form' => $form->createView(),
                'category' => $category,
            ]
        );
    }
    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Entity\NoteCategory                      $category           NoteCategory entity
     * @param \App\Repository\NoteCategoryRepository        $categoryRepository NoteCategory repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/note-category-delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="note_category_delete",
     * )
     */
    public function delete(Request $request, NoteCategory $category, NoteCategoryRepository $categoryRepository): Response
    {
        if ($category->getNotes()->count()) {
            $this->addFlash('warning', 'message_category_contains_notes');

            return $this->redirectToRoute('note_category_index');
        }
        $form = $this->createForm(NoteCategoryType::class, $category, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->delete($category);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('note_category_index');
        }

        return $this->render(
            'note-category/delete.html.twig',
            [
                'form' => $form->createView(),
                'category' => $category,
            ]
        );
    }
}