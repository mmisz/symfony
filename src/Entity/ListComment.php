<?php

namespace App\Entity;

use App\Repository\ListCommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
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
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=ToDoList::class, inversedBy="listComments")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\Type(type="App\Entity\ToDoList")
     */
    private $to_do_list;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $creation;

    /**
     *  Getter for id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for content.
     *
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Setter for content.
     *
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Getter for to_do_list.
     *
     * @return ToDoList|null
     */
    public function getToDoList(): ?ToDoList
    {
        return $this->to_do_list;
    }

    /**
     * Setter for to_do_list.
     *
     * @param ToDoList|null $to_do_list
     * @return $this
     */
    public function setToDoList(?ToDoList $to_do_list): self
    {
        $this->to_do_list = $to_do_list;

        return $this;
    }

    /**
     * getter for Creation.
     *
     * @return \DateTimeInterface|null
     */
    public function getCreation(): ?\DateTimeInterface
    {
        return $this->creation;
    }

    /**
     * Setter for Creation.
     *
     * @param \DateTimeInterface $creation
     * @return $this
     */
    public function setCreation(\DateTimeInterface $creation): self
    {
        $this->creation = $creation;

        return $this;
    }
}
