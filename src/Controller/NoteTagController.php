<?php
/**
 * NoteTag controller.
 */

namespace App\Controller;

use App\Entity\NoteTag;
use App\Form\NoteSingleTagType;
use App\Repository\NoteRepository;
use App\Repository\NoteTagRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class NoteTagController.
 *
 * @Route("/note-tag")
 */
class NoteTagController extends AbstractController
{
    /**
     * index action.
     *
     * @param Request $request
     * @param NoteTagRepository $tagRepository
     * @param PaginatorInterface $paginator
     * @return Response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="note_tag_index",
     * )
     */
    public function index(Request $request, NoteTagRepository $tagRepository, PaginatorInterface $paginator): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $pagination = $paginator->paginate(
                $tagRepository->queryAll(),
                $request->query->getInt('page', 1),
                NoteTagRepository::PAGINATOR_ITEMS_PER_PAGE
            );
        } else {
            $pagination = $paginator->paginate(
                $tagRepository->findTagsForAuthor($this->getUser()),
                $request->query->getInt('page', 1),
                NoteRepository::PAGINATOR_ITEMS_PER_PAGE
            );
        }

        return $this->render(
            'note-tag/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param NoteTag $tag
     * @param NoteRepository $noteRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="note_tag_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(NoteTag $tag, NoteRepository $noteRepository, PaginatorInterface $paginator, Request $request): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $pagination = $paginator->paginate(
                $noteRepository->findByTag($tag),
                $request->query->getInt('page', 1),
                NoteRepository::PAGINATOR_ITEMS_PER_PAGE
            );
        } else {
            $pagination = $paginator->paginate(
                $noteRepository->queryByAuthorAndTag($this->getUser(), $tag),
                $request->query->getInt('page', 1),
                NoteRepository::PAGINATOR_ITEMS_PER_PAGE
            );
        }

        return $this->render(
            'note-tag/show.html.twig',
            [
                'pagination' => $pagination,
                'tag' => $tag, ]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request
     * @param NoteTag $noteTag
     * @param NoteTagRepository $noteTagRepository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/note-tag-edit",
     *     methods={"GET", "PUT"},
     *     name="note_tag_edit",
     *     requirements={"id": "[1-9]\d*"},
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, NoteTag $noteTag, NoteTagRepository $noteTagRepository): Response
    {
        $form = $this->createForm(NoteSingleTagType::class, $noteTag, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $noteTagRepository->save($noteTag);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('note_tag_index');
        }

        return $this->render(
            'note-tag/edit.html.twig',
            [
                'form' => $form->createView(),
                'noteTag' => $noteTag,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request
     * @param NoteTag $noteTag
     * @param NoteTagRepository $noteTagRepository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/note-tag-delete",
     *     methods={"GET", "DELETE"},
     *     name="note_tag_delete",
     *     requirements={"id": "[1-9]\d*"},
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, NoteTag $noteTag, NoteTagRepository $noteTagRepository): Response
    {
        $form = $this->createForm(FormType::class, $noteTag, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $noteTagRepository->delete($noteTag);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('note_tag_index');
        }

        return $this->render(
            'note-tag/delete.html.twig',
            [
                'form' => $form->createView(),
                'noteTag' => $noteTag,
            ]
        );
    }

    /**
     * Create action
     *
     * @param Request $request
     * @param NoteTagRepository $noteTagRepository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/note-tag-create",
     *     methods={"GET", "POST"},
     *     name="note_tag_create",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(Request $request, NoteTagRepository $noteTagRepository): Response
    {
        $noteTag = new NoteTag();
        $form = $this->createForm(NoteSingleTagType::class, $noteTag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $noteTagRepository->save($noteTag);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('note_tag_index');
        }

        return $this->render(
            'note-tag/create.html.twig',
            ['form' => $form->createView()]
        );
    }
}
