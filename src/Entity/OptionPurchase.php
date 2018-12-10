<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OptionRepository")
 */
class OptionPurchase
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", unique=true)
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"api"});
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", inversedBy="options")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductCartOption", mappedBy="optionPurchase", orphanRemoval=true)
     */
    private $productCartOptions;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->productCartOptions = new ArrayCollection();
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
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
        }

        return $this;
    }

    /**
     * @return Collection|ProductCartOption[]
     */
    public function getProductCartOptions(): Collection
    {
        return $this->productCartOptions;
    }

    public function addProductCartOption(ProductCartOption $productCartOption): self
    {
        if (!$this->productCartOptions->contains($productCartOption)) {
            $this->productCartOptions[] = $productCartOption;
            $productCartOption->setOptionPurchase($this);
        }

        return $this;
    }

    public function removeProductCartOption(ProductCartOption $productCartOption): self
    {
        if ($this->productCartOptions->contains($productCartOption)) {
            $this->productCartOptions->removeElement($productCartOption);
            // set the owning side to null (unless already changed)
            if ($productCartOption->getOptionPurchase() === $this) {
                $productCartOption->setOptionPurchase(null);
            }
        }

        return $this;
    }
}
