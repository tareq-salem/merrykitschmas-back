<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductCartRepository")
 */
class ProductCart
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductCartOption", mappedBy="cart", orphanRemoval=true)
     */
    private $productCartOptionsCart;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductCartOption", mappedBy="product", orphanRemoval=true)
     */
    private $productCartOptionsProduct;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cart", inversedBy="productCarts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cart;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="product_carts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    public function __construct()
    {
        $this->productCartOptionsCart = new ArrayCollection();
        $this->productCartOptionsProduct = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return Collection|ProductCartOption[]
     */
    public function getProductCartOptionsCart(): Collection
    {
        return $this->productCartOptionsCart;
    }

    public function addProductCartOptionCart(ProductCartOption $productCartOption): self
    {
        if (!$this->productCartOptionsCart->contains($productCartOption)) {
            $this->productCartOptionsCart[] = $productCartOption;
            $productCartOption->setCart($this);
        }

        return $this;
    }

    public function removeProductCartOptionCart(ProductCartOption $productCartOption): self
    {
        if ($this->productCartOptionsCart->contains($productCartOption)) {
            $this->productCartOptionsCart->removeElement($productCartOption);
            // set the owning side to null (unless already changed)
            if ($productCartOption->getCart() === $this) {
                $productCartOption->setCart(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProductCartOption[]
     */
    public function getProductCartOptionsProduct(): Collection
    {
        return $this->productCartOptionsProduct;
    }

    public function addProductCartOptionProduct(ProductCartOption $productCartOption): self
    {
        if (!$this->productCartOptionsProduct->contains($productCartOption)) {
            $this->productCartOptionsProduct[] = $productCartOption;
            $productCartOption->setProduct($this);
        }

        return $this;
    }

    public function removeProductCartOptionProduct(ProductCartOption $productCartOption): self
    {
        if ($this->productCartOptionsProduct->contains($productCartOption)) {
            $this->productCartOptionsProduct->removeElement($productCartOption);
            // set the owning side to null (unless already changed)
            if ($productCartOption->getProduct() === $this) {
                $productCartOption->setProduct(null);
            }
        }

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
