<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PurchaseRepository")
 */
class Purchase
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", unique=true)
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="purchases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductPurchase", mappedBy="purchase")
     */
    private $productPurchases;

    public function __construct()
    {
        $this->productPurchases = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|ProductPurchase[]
     */
    public function getProductPurchases(): Collection
    {
        return $this->productPurchases;
    }

    public function addProductPurchase(ProductPurchase $productPurchase): self
    {
        if (!$this->productPurchases->contains($productPurchase)) {
            $this->productPurchases[] = $productPurchase;
            $productPurchase->setPurchase($this);
        }

        return $this;
    }

    public function removeProductPurchase(ProductPurchase $productPurchase): self
    {
        if ($this->productPurchases->contains($productPurchase)) {
            $this->productPurchases->removeElement($productPurchase);
            // set the owning side to null (unless already changed)
            if ($productPurchase->getPurchase() === $this) {
                $productPurchase->setPurchase(null);
            }
        }

        return $this;
    }
}
