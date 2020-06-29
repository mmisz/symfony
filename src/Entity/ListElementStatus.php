<?php

namespace App\Entity;

use App\Repository\ListElementStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ListElementStatusRepository", repositoryClass=ListElementStatusRepository::class)
 */
class ListElementStatus
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
     * @ORM\OneToMany(targetEntity=ListElement::class, mappedBy="status")
     */
    private $listElements;

    public function __construct()
    {
        $this->listElements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for Name.
     *
     * @param /ListElementStatus
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Collection|ListElement[]
     */
    public function getListElements(): Collection
    {
        return $this->listElements;
    }

    /**
     * @return $this
     */
    public function addListElement(ListElement $listElement): self
    {
        if (!$this->listElements->contains($listElement)) {
            $this->listElements[] = $listElement;
            $listElement->setStatus($this);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function removeListElement(ListElement $listElement): self
    {
        if ($this->listElements->contains($listElement)) {
            $this->listElements->removeElement($listElement);
            // set the owning side to null (unless already changed)
            if ($listElement->getStatus() === $this) {
                $listElement->setStatus(null);
            }
        }

        return $this;
    }
}
