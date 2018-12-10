<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", unique=true)
     * @Groups({"api"});
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api"});
     */
    private $name;

    /**
     * @ORM\Column(type="float", scale=2)
     * @Groups({"api"});
     */
    private $price;

    /**
     * @ORM\Column(type="text", length=255)
     * @Groups({"api"});
     */
    private $description;

    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     * @Groups({"api"});
     */
    private $image;

   /**
     * @ORM\Column(type="smallint")
    * @Groups({"api"});
     */
    private $visible;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"api"});
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"api"});
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="product", orphanRemoval=true, cascade={"persist"})
     * @Groups({"api"});
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\OptionPurchase", mappedBy="products")
     * @Groups({"api"});
     */
    private $options;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Subcategory", mappedBy="products")
     */
    private $subcategories;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Theme", mappedBy="products")
     */
    private $themes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductPurchase", mappedBy="product")
     */
    private $productPurchases;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductParameter", mappedBy="product", orphanRemoval=true, cascade={"persist"})
     * @Groups({"api"});
     */
    private $productParameters;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", mappedBy="products")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductCart", mappedBy="product", orphanRemoval=true)
     */
    private $product_carts;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->options = new ArrayCollection();
        $this->subcategories = new ArrayCollection();
        $this->themes = new ArrayCollection();
        $this->productPurchases = new ArrayCollection();
        $this->productParameters = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->product_carts = new ArrayCollection();
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

     public function getVisible(): ?int
    {
        return $this->visible;
    }

    public function setVisible(int $visible): self
    {
        $this->visible = $visible;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setProduct($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getProduct() === $this) {
                $comment->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|OptionPurchase[]
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(OptionPurchase $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options[] = $option;
            $option->addProduct($this);
        }

        return $this;
    }

    public function removeOption(OptionPurchase $option): self
    {
        if ($this->options->contains($option)) {
            $this->options->removeElement($option);
            $option->removeProduct($this);
        }

        return $this;
    }

    /**
     * @return Collection|Subcategory[]
     */
    public function getSubcategories(): Collection
    {
        return $this->subcategories;
    }

    public function addSubcategory(Subcategory $subcategory): self
    {
        if (!$this->subcategories->contains($subcategory)) {
            $this->subcategories[] = $subcategory;
            $subcategory->addProduct($this);
        }

        return $this;
    }

    public function removeSubcategory(Subcategory $subcategory): self
    {
        if ($this->subcategories->contains($subcategory)) {
            $this->subcategories->removeElement($subcategory);
            $subcategory->removeProduct($this);
        }

        return $this;
    }

    /**
     * @return Collection|Theme[]
     */
    public function getThemes(): Collection
    {
        return $this->themes;
    }

    public function addTheme(Theme $theme): self
    {
        if (!$this->themes->contains($theme)) {
            $this->themes[] = $theme;
            $theme->addProduct($this);
        }

        return $this;
    }

    public function removeTheme(Theme $theme): self
    {
        if ($this->themes->contains($theme)) {
            $this->themes->removeElement($theme);
            $theme->removeProduct($this);
        }

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
            $productPurchase->setProduct($this);
        }

        return $this;
    }

    public function removeProductPurchase(ProductPurchase $productPurchase): self
    {
        if ($this->productPurchases->contains($productPurchase)) {
            $this->productPurchases->removeElement($productPurchase);
            // set the owning side to null (unless already changed)
            if ($productPurchase->getProduct() === $this) {
                $productPurchase->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProductParameter[]
     */
    public function getProductParameters(): Collection
    {
        return $this->productParameters;
    }

    public function addProductParameter(ProductParameter $productParameter): self
    {
        if (!$this->productParameters->contains($productParameter)) {
            $this->productParameters[] = $productParameter;
            $productParameter->setProduct($this);
        }

        return $this;
    }

    public function removeProductParameter(ProductParameter $productParameter): self
    {
        if ($this->productParameters->contains($productParameter)) {
            $this->productParameters->removeElement($productParameter);
            // set the owning side to null (unless already changed)
            if ($productParameter->getProduct() === $this) {
                $productParameter->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addProduct($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
            $category->removeProduct($this);
        }

        return $this;
    }

    /**
     * @return Collection|ProductCart[]
     */
    public function getProductCarts(): Collection
    {
        return $this->product_carts;
    }

    public function addProductCart(ProductCart $productCart): self
    {
        if (!$this->product_carts->contains($productCart)) {
            $this->product_carts[] = $productCart;
            $productCart->setProduct($this);
        }

        return $this;
    }

    public function removeProductCart(ProductCart $productCart): self
    {
        if ($this->product_carts->contains($productCart)) {
            $this->product_carts->removeElement($productCart);
            // set the owning side to null (unless already changed)
            if ($productCart->getProduct() === $this) {
                $productCart->setProduct(null);
            }
        }

        return $this;
    }


}
