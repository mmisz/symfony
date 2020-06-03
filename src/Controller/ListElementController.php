<?php


namespace App\Controller;

use App\Entity\ListElement;
use App\Entity\ToDoList;
use App\Repository\ListElementRepository;
use App\Repository\ListStatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Form\ListElementType;

/**
 * Class ListElementController
 * @package App\Controller
 */
class ListElementController extends AbstractController
{
    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\ListElement $listElement
     * @param \App\Repository\ListElementRepository $listElementRepository
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route(
     *     "/list-element-edit/{id}",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="list_element_edit",
     * )
     */
    public function edit(Request $request, ListElement $listElement, ListElementRepository $listElementRepository): Response
    {
        $form = $this->createForm(ListElementType::class, $listElement, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $listElementRepository->save($listElement);

            $this->addFlash('success', 'message_updated_successfully');
            $toDoList = $listElement->getToDoList();
            return $this->redirectToRoute('to_do_show',['id'=>$toDoList->getId()]);
        }

        return $this->render(
            'list-element/edit.html.twig',
            [
                'form' => $form->createView(),
                'listElement' => $listElement,
                'listId' => $listElement->getToDoList()->getId(),
            ]
        );
    }
    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Entity\ListElement $listElement
     * @param \App\Repository\ListElementRepository $listElementRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/list-element-delete/{id}",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="list_element_delete",
     * )
     */
    public function delete(Request $request, ListElement $listElement, ListElementRepository $listElementRepository): Response
    {
        $form = $this->createForm(ListElementType::class, $listElement, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if (!$form->isSubmitted() && $request->isMethod('DELETE')) {
            $form->submit($request->request->get($form->getName()));
        }
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', 'message_deleted_successfully');
            $listElementRepository->delete($listElement);
            $toDoList = $listElement->getToDoList();
            return $this->redirectToRoute('to_do_show',['id'=>$toDoList->getId()]);
        }
        return $this->render(
            'list-element/delete.html.twig',
            [
                'form' => $form->createView(),
                'listElement' => $listElement,
                'listId' => $listElement->getToDoList()->getId(),
            ]
        );
    }

    /**
     * Create action.
     *
     * @param ToDoList $toDoList
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Repository\ListElementRepository $listElementRepository ListElement repository
     * @param ListStatusRepository $listStatusRepository
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route(
     *     "{id}/list-element-create",
     *     methods={"GET", "POST"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="list_element_create",
     * )
     */
    public function create(ToDoList $toDoList, Request $request, ListElementRepository $listElementRepository, ListElementRepository $listStatusRepository): Response
    {
        $listElement = new ListElement();
        $form = $this->createForm(ListElementType::class, $listElement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $listElement->setToDoList($toDoList);
            $this->addFlash('success', 'message_created_successfully');
            $listElement->setCreation(new \DateTime());
            $listElement->setStatus($listStatusRepository->findOneBy(['name'=>'to do']));
            $listElementRepository->save($listElement);
            return $this->redirectToRoute('to_do_show',['id'=>$toDoList->getId()]);
        }

        return $this->render(
            'list-element/create.html.twig',
            ['form' => $form->createView(),
                'listId' => $toDoList->getId(),
            ]
        );
    }
}