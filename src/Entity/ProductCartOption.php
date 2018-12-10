<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductCartOptionRepository")
 */
class ProductCartOption
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ProductCart", inversedBy="productCartOptionsCart")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cart;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ProductCart", inversedBy="productCartOptionsProduct")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OptionPurchase", inversedBy="productCartOptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $optionPurchase;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCart(): ?ProductCart
    {
        return $this->cart;
    }

    public function setCart(?ProductCart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }

    public function getProduct(): ?ProductCart
    {
        return $this->product;
    }

    public function setProduct(?ProductCart $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getOptionPurchase(): ?OptionPurchase
    {
        return $this->optionPurchase;
    }

    public function setOptionPurchase(?OptionPurchase $optionPurchase): self
    {
        $this->optionPurchase = $optionPurchase;

        return $this;
    }
}
