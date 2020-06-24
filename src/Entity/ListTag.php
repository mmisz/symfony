<?php

namespace App\Entity;

use App\Form\ToDoType;
use App\Repository\ListTagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class ListTag.
 *
 * @ORM\Entity(repositoryClass="App\Repository\ListTagRepository")
 * @ORM\Table(name="list_tags")
 *
 * @UniqueEntity(fields={"name"})
 */
class ListTag
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
     * name.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=64,
     * )
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="1",
     *     max="64",
     * )
     */
    private $name;


    /**
     * ToDoLists.
     *
     * @var \Doctrine\Common\Collections\ArrayCollection|\App\Entity\ToDoList[] ToDoLists
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\ToDoList", mappedBy="listTag")
     *
     * Assert\Type(type="Doctrine\Common\Collections\ArrayCollection")
     */
    private $toDoLists;


    public function __construct()
    {
        $this->toDoLists = new ArrayCollection();
    }

    /**
     * Getter for Id.
     *
     * @return int|null Result
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
     * Add toDoList to collection.
     *
     * @param \App\Entity\ToDoList $toDoList ToDoList entity
     */
    public function addToDoList(ToDoList $toDoList): void
    {
        if (!$this->toDoLists->contains($toDoList)) {
            $this->toDoLists[] = $toDoList;
            $toDoList->addListTag($this);
        }
    }

    /**
     * Remove toDoList from collection.
     *
     * @param \App\Entity\ToDoList $toDoList ToDoList entity
     */
    public function removeToDoList(ToDoList $toDoList): void
    {
        if ($this->toDoLists->contains($toDoList)) {
            $this->toDoLists->removeElement($toDoList);
            $toDoList->removeListTag($this);
        }
    }
    /**
     * @return Collection|ToDoList[]
     */
    public function getToDoLists(): Collection
    {
        return $this->toDoLists;
    }
}
