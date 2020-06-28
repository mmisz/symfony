<?php
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminPasswordType;
use App\Form\UserEmailType;
use App\Form\UserPasswordType;
use App\Repository\NoteRepository;
use App\Repository\ToDoListRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserController.
 *
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
     * @param \App\Repository\UserRepository            $userRepository User repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator      Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="user_index",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(Request $request, UserRepository $userRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $userRepository->queryAll(),
            $request->query->getInt('page', 1),
            UserRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'user/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * User action.
     *
     * @param User $usr User entity
     *
     * @Route("/{id}", name="user_show")
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('EDIT', usr)")
     */
    public function show(User $usr): Response
    {
        return $this->render(
            'user/show.html.twig',
            ['user' => $usr]
        );
    }

    /**
     * Edit email action.
     *
     * @param Request $request
     * @param User $usr
     * @param UserRepository $userRepository
     * @param TranslatorInterface $translator
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/user-email",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="user_email",
     * )
     * @Security("is_granted('ROLE_ADMIN') or is_granted('EDIT', usr)")
     */
    public function editEmail(Request $request, User $usr, UserRepository $userRepository, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(UserEmailType::class, $usr, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($usr);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('user_show', ['id' => $usr->getId()]);
        }

        return $this->render(
            'user/email.html.twig',
            [
                'form' => $form->createView(),
                'user' => $usr,
            ]
        );
    }

    /**
     * Edit password action.
     *
     * @param Request $request
     * @param User $usr
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param TranslatorInterface $translator
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/user-password",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="user_password",
     * )
     * @Security("is_granted('ROLE_ADMIN') or is_granted('EDIT', usr)")
     */
    public function editPassword(Request $request, User $usr, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder, TranslatorInterface $translator): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $type = AdminPasswordType::class;
        } else {
            $type = UserPasswordType::class;
        }
        $form = $this->createForm($type, $usr, ['method' => 'PUT']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('new_password')->getData();
            if ($this->isGranted('ROLE_ADMIN')) {
                $usr->setPassword(
                    $passwordEncoder->encodePassword(
                        $usr,
                        $newPassword
                    )
                );
                $userRepository->save($usr);
                $this->addFlash('success', 'message_updated_successfully');

                return $this->redirectToRoute('user_index');
            } else {
                $usr = $this->getUser();
                $oldPassword = $form->get('old_password')->getData();
                $checkPass = $passwordEncoder->isPasswordValid($usr, $oldPassword);
                if (true === $checkPass) {
                    $usr->setPassword(
                        $passwordEncoder->encodePassword(
                            $usr,
                            $newPassword
                        )
                    );
                    $userRepository->save($usr);
                    $this->addFlash('success', 'message_updated_successfully');

                    return $this->redirectToRoute('user_show', ['id' => $usr->getId()]);
                } else {
                    $this->addFlash('warning', $translator->trans('message_wrong_password'));
                }
            }
        }

        return $this->render(
            'user/password.html.twig',
            [
                'form' => $form->createView(),
                'user' => $usr,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request
     * @param User $user
     * @param UserRepository $userRepository
     * @param ToDoListRepository $toDoListRepository
     * @param NoteRepository $noteRepository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/user-delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="user_delete",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, User $user, UserRepository $userRepository, ToDoListRepository $toDoListRepository, NoteRepository $noteRepository): Response
    {
        $form = $this->createForm(UserEmailType::class, $user, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $lists = $toDoListRepository->findBy(['author' => $user]);
            $notes = $noteRepository->findBy(['author' => $user]);
            foreach ($notes as $note) {
                $noteRepository->delete($note);
            }
            foreach ($lists as $list) {
                $toDoListRepository->delete($list);
            }
            $userRepository->delete($user);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'user/delete.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
}
