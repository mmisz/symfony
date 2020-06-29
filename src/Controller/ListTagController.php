<?php
/**
 * ListTag controller.
 */

namespace App\Controller;

use App\Entity\ListTag;
use App\Form\ListSingleTagType;
use App\Repository\ListTagRepository;
use App\Repository\ToDoListRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ListTagController.
 *
 * @Route("/list-tag")
 */
class ListTagController extends AbstractController
{
    /**
     * Index action.
     *
     * @param Request $request       HTTP request
     * @param ListTagRepository $tagRepository ListTag repository
     * @param PaginatorInterface $paginator     Paginator
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="list_tag_index",
     * )
     */
    public function index(Request $request, ListTagRepository $tagRepository, PaginatorInterface $paginator): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $pagination = $paginator->paginate(
                $tagRepository->queryAll(),
                $request->query->getInt('page', 1),
                ListTagRepository::PAGINATOR_ITEMS_PER_PAGE
            );
        } else {
            $pagination = $paginator->paginate(
                $tagRepository->findTagsForAuthor($this->getUser()),
                $request->query->getInt('page', 1),
                ListTagRepository::PAGINATOR_ITEMS_PER_PAGE
            );
        }

        return $this->render(
            'list-tag/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param ListTag $tag
     * @param ToDoListRepository $toDoListRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="list_tag_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(ListTag $tag, ToDoListRepository $toDoListRepository, PaginatorInterface $paginator, Request $request): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $pagination = $paginator->paginate(
                $toDoListRepository->findByTag($tag),
                $request->query->getInt('page', 1),
                ToDoListRepository::PAGINATOR_ITEMS_PER_PAGE
            );
        } else {
            $pagination = $paginator->paginate(
                $toDoListRepository->queryByAuthorAndTag($this->getUser(), $tag),
                $request->query->getInt('page', 1),
                ToDoListRepository::PAGINATOR_ITEMS_PER_PAGE
            );
        }

        return $this->render(
            'list-tag/show.html.twig',
            [
                'pagination' => $pagination,
                'tag' => $tag, ]
        );
    }

    /**
     * Edit action
     *
     * @param Request $request
     * @param ListTag $listTag
     * @param ListTagRepository $listTagRepository
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * * @Route(
     *     "/{id}/list-tag-edit",
     *     methods={"GET", "PUT"},
     *     name="list_tag_edit",
     *     requirements={"id": "[1-9]\d*"},
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, ListTag $listTag, ListTagRepository $listTagRepository): Response
    {
        $form = $this->createForm(ListSingleTagType::class, $listTag, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $listTagRepository->save($listTag);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('list_tag_index');
        }

        return $this->render(
            'list-tag/edit.html.twig',
            [
                'form' => $form->createView(),
                'listTag' => $listTag,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request           HTTP request
     * @param ListTag $listTag           ListTag entity
     * @param ListTagRepository $listTagRepository ListTag repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/list-tag-delete",
     *     methods={"GET", "DELETE"},
     *     name="list_tag_delete",
     *     requirements={"id": "[1-9]\d*"},
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, ListTag $listTag, ListTagRepository $listTagRepository): Response
    {
        $form = $this->createForm(FormType::class, $listTag, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $listTagRepository->delete($listTag);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('list_tag_index');
        }

        return $this->render(
            'list-tag/delete.html.twig',
            [
                'form' => $form->createView(),
                'listTag' => $listTag,
            ]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request           HTTP request
     * @param ListTagRepository $listTagRepository ListTag repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/list-tag-create",
     *     methods={"GET", "POST"},
     *     name="list_tag_create",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(Request $request, ListTagRepository $listTagRepository): Response
    {
        $listTag = new ListTag();
        $form = $this->createForm(ListSingleTagType::class, $listTag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $listTagRepository->save($listTag);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('list_tag_index');
        }

        return $this->render(
            'list-tag/create.html.twig',
            ['form' => $form->createView()]
        );
    }
}
