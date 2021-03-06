<?php
/**
 * Note controller.
 */

namespace App\Controller;

use App\Entity\Note;
use App\Form\NoteDeleteType;
use App\Form\NoteType;
use App\Repository\NoteRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class Note.
 *
 * @Route("/note")
 */
class NoteController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
     * @param \App\Repository\NoteRepository            $noteRepository Note repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator      Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="note_index",
     * )
     */
    public function index(Request $request, NoteRepository $noteRepository, PaginatorInterface $paginator): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $pagination = $paginator->paginate(
                $noteRepository->queryAll(),
                $request->query->getInt('page', 1),
                $noteRepository::PAGINATOR_ITEMS_PER_PAGE
            );
        } else {
            $pagination = $paginator->paginate(
                $noteRepository->queryByAuthor($this->getUser()),
                $request->query->getInt('page', 1),
                $noteRepository::PAGINATOR_ITEMS_PER_PAGE
            );
        }

        return $this->render(
            'note/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\Note                          $note    Note entity
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="note_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('VIEW', note)")
     */
    public function show(Note $note, Request $request): Response
    {
        return $this->render(
            'note/show.html.twig',
            ['note' => $note]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request
     * @param Note $note
     * @param NoteRepository $noteRepository
     * @return Response
     * @throws \Exception
     * @Route(
     *     "/note-edit/{id}",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="note_edit",
     * )
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('EDIT', note)")
     */
    public function edit(Request $request, Note $note, NoteRepository $noteRepository): Response
    {
        $form = $this->createForm(NoteType::class, $note, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $note->setLastUpdate(new \DateTime());
            $noteRepository->save($note);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('note_index');
        }

        return $this->render(
            'note/edit.html.twig',
            [
                'form' => $form->createView(),
                'note' => $note,
            ]
        );
    }

    /**
     * Delete action.
     * @param Request $request
     * @param Note $note
     * @param NoteRepository $noteRepository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/note-delete/{id}",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="note_delete",
     * )
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('DELETE', note)")
     */
    public function delete(Request $request, Note $note, NoteRepository $noteRepository): Response
    {
        $form = $this->createForm(FormType::class, $note, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if (!$form->isSubmitted() && $request->isMethod('DELETE')) {
            $form->submit($request->request->get($form->getName()));
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'message_deleted_successfully');
            $noteRepository->delete($note);

            return $this->redirectToRoute('note_index');
        }

        return $this->render(
            'note/delete.html.twig',
            [
                'form' => $form->createView(),
                'note' => $note,
            ]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request
     * @param NoteRepository $noteRepository
     * @return Response
     * @throws \Exception
     *
     * @Route(
     *     "/note-create",
     *     methods={"GET", "POST"},
     *     name="note_create",
     * )
     */
    public function create(Request $request, NoteRepository $noteRepository): Response
    {
        $note = new Note();
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $note->setCreation(new \DateTime());
            $note->setAuthor($this->getUser());
            $noteRepository->save($note);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('note_index');
        }

        return $this->render(
            'note/create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
