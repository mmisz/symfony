<?php

namespace App\Entity;

use App\Repository\ListElementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ListElementRepository::class)
 * @ORM\Table(name="list_elements")
 */
class ListElement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=ToDoList::class, inversedBy="listElements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $to_do_list;

    /**
     * @ORM\Column(type="smallint")
     */
    private $done;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getToDoList(): ?ToDoList
    {
        return $this->to_do_list;
    }

    public function setToDoList(?ToDoList $to_do_list): self
    {
        $this->to_do_list = $to_do_list;

        return $this;
    }

    public function getDone(): ?int
    {
        return $this->done;
    }

    public function setDone(int $done): self
    {
        $this->done = $done;

        return $this;
    }
}
