<?php
/**
 * NoteCategory controller.
 */

namespace App\Controller;

use App\Entity\Note;
use App\Entity\NoteCategory;
use App\Form\NoteCategoryType;
use App\Repository\NoteCategoryRepository;
use App\Repository\NoteRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class NoteCategory.
 *
 * @Route("/note-category")
 */
class NoteCategoryController extends AbstractController
{
    /**
     * index action.
     *
     * @param Request $request
     * @param NoteCategoryRepository $categoryRepository
     * @param PaginatorInterface $paginator
     * @return Response
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
     * @param NoteCategory $category
     * @param NoteRepository $noteRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     *
     * @Route(
     *     "/{id}/note-category",
     *     methods={"GET"},
     *     name="note_category_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(NoteCategory $category, NoteRepository $noteRepository, PaginatorInterface $paginator, Request $request): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $pagination = $paginator->paginate(
                $noteRepository->findBy(['category' => $category]),
                $request->query->getInt('page', 1),
                NoteRepository::PAGINATOR_ITEMS_PER_PAGE
            );
        } else {
            $pagination = $paginator->paginate(
                $noteRepository->queryByAuthorAndCategory($this->getUser(), $category),
                $request->query->getInt('page', 1),
                NoteRepository::PAGINATOR_ITEMS_PER_PAGE
            );
        }

        return $this->render(
            'note-category/show.html.twig',
            [
                'pagination' => $pagination,
                'category' => $category,
            ]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request
     * @param NoteCategoryRepository $categoryRepository
     * @param TranslatorInterface $translator
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/note-category-create",
     *     methods={"GET", "POST"},
     *     name="note_category_create",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(Request $request, NoteCategoryRepository $categoryRepository, TranslatorInterface $translator): Response
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
     * Edit action
     *
     * @param Request $request
     * @param NoteCategory $category
     * @param NoteCategoryRepository $categoryRepository
     * @param TranslatorInterface $translator
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/note-category-edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="note_category_edit",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, NoteCategory $category, NoteCategoryRepository $categoryRepository, TranslatorInterface $translator): Response
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
     * @param Request $request
     * @param NoteCategory $category
     * @param NoteCategoryRepository $categoryRepository
     * @param TranslatorInterface $translator
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/note-category-delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="note_category_delete",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, NoteCategory $category, NoteCategoryRepository $categoryRepository, TranslatorInterface $translator): Response
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
