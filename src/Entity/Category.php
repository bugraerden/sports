<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\OneToMany(targetEntity=Blog::class, mappedBy="category")
     */
    private $category;

    public function __construct()
    {
        $this->category = new ArrayCollection();
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

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * @return Collection<int, Blog>
     */
    public function getAge(): Collection
    {
        return $this->age;
    }

    public function addAge(Blog $age): self
    {
        if (!$this->age->contains($age)) {
            $this->age[] = $age;
            $age->setCategory($this);
        }

        return $this;
    }

    public function removeAge(Blog $age): self
    {
        if ($this->age->removeElement($age)) {
            // set the owning side to null (unless already changed)
            if ($age->getCategory() === $this) {
                $age->setCategory(null);
            }
        }

        return $this;
    }
}
