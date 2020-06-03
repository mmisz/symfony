<?php

namespace App\Entity;

use App\Repository\ListStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ListStatusRepository::class)
 */
class ListStatus
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=ToDoList::class, mappedBy="status")
     */
    private $toDoLists;

    public function __construct()
    {
        $this->toDoLists = new ArrayCollection();
    }

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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|ToDoList[]
     */
    public function getToDoLists(): Collection
    {
        return $this->toDoLists;
    }

    /**
     * @param ToDoList $toDoList
     * @return $this
     */
    public function addToDoList(ToDoList $toDoList): self
    {
        if (!$this->toDoLists->contains($toDoList)) {
            $this->toDoLists[] = $toDoList;
            $toDoList->setStatus($this);
        }

        return $this;
    }

    /**
     * @param ToDoList $toDoList
     * @return $this
     */
    public function removeToDoList(ToDoList $toDoList): self
    {
        if ($this->toDoLists->contains($toDoList)) {
            $this->toDoLists->removeElement($toDoList);
            // set the owning side to null (unless already changed)
            if ($toDoList->getStatus() === $this) {
                $toDoList->setStatus(null);
            }
        }

        return $this;
    }
}
