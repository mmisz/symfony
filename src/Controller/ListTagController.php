<?php
/**
 * ListTag controller.
 */

namespace App\Controller;

use App\Entity\ListTag;
use App\Form\ListSingleTagType;
use App\Repository\ListTagRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Repository\ListTagRepository        $listTagRepository ListTag repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator          Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="list_tag_index",
     * )
     */
    public function index(Request $request, ListTagRepository $tagRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $tagRepository->queryAll(),
            $request->query->getInt('page', 1),
            ListTagRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'list-tag/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\ListTag $tag Tag entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="list_tag_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(ListTag $tag): Response
    {
        return $this->render(
            'list-tag/show.html.twig',
            ['tag' => $tag]
        );
    }
    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Entity\ListTag                   $listTag           ListTag entity
     * @param \App\Repository\ListTagRepository        $listTagRepository ListTag repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/list-tag-edit",
     *     methods={"GET", "PUT"},
     *     name="list_tag_edit",
     *     requirements={"id": "[1-9]\d*"},
     * )
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
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Entity\ListTag                      $listTag           ListTag entity
     * @param \App\Repository\ListTagRepository        $listTagRepository ListTag repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/list-tag-delete",
     *     methods={"GET", "DELETE"},
     *     name="list_tag_delete",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function delete(Request $request, ListTag $listTag, ListTagRepository $listTagRepository): Response
    {
        $form = $this->createForm(ListSingleTagType::class, $listTag, ['method' => 'DELETE']);
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
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Repository\ListTagRepository        $listTagRepository ListTag repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/list-tag-create",
     *     methods={"GET", "POST"},
     *     name="list_tag_create",
     * )
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