<?php

namespace App\Entity;

use App\Repository\ListCommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ListCommentRepository::class)
 * @ORM\Table(name="list_comments")
 */
class ListComment
{
    /**
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=ToDoList::class, inversedBy="listComments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $to_do_list;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return ToDoList|null
     */
    public function getToDoList(): ?ToDoList
    {
        return $this->to_do_list;
    }

    /**
     * @param ToDoList|null $to_do_list
     * @return $this
     */
    public function setToDoList(?ToDoList $to_do_list): self
    {
        $this->to_do_list = $to_do_list;

        return $this;
    }

    public function getCreation(): ?\DateTimeInterface
    {
        return $this->creation;
    }

    public function setCreation(\DateTimeInterface $creation): self
    {
        $this->creation = $creation;

        return $this;
    }
}
