<?php

namespace App\Entity;

use App\Repository\ListTagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ListTagRepository::class)
 * @ORM\Table(name="list_tags")
 */
class ListTag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=ToDoList::class, inversedBy="listTags")
     */
    private $toDoList;

    public function __construct()
    {
        $this->toDoList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|ToDoList[]
     */
    public function getToDoList(): Collection
    {
        return $this->toDoList;
    }

    public function addToDoList(ToDoList $toDoList): self
    {
        if (!$this->toDoList->contains($toDoList)) {
            $this->toDoList[] = $toDoList;
        }

        return $this;
    }

    public function removeToDoList(ToDoList $toDoList): self
    {
        if ($this->toDoList->contains($toDoList)) {
            $this->toDoList->removeElement($toDoList);
        }

        return $this;
    }
}
