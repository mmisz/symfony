<?php
/**
 * ListTag controller.
 */

namespace App\Controller;

use App\Entity\ListTag;
use App\Form\ListSingleTagType;
use App\Repository\ListTagRepository;
use Knp\Component\Pager\PaginatorInterface;
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
}