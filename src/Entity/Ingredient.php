<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * @ORM\Entity(repositoryClass=IngredientRepository::class)
 */
class Ingredient
{
    /**
     * @Groups({"meal"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"meal"})
     * @ORM\Column(type="float")
     */
    private $quantity;

    /**
     * @Groups({"meal"})
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="ingredients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @Ignore()
     * @ORM\ManyToOne(targetEntity=Meal::class, inversedBy="ingredients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $meal;

    public function __construct()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;

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

    public function getMeal(): ?Meal
    {
        return $this->meal;
    }

    public function setMeal(?Meal $meal): self
    {
        $this->meal = $meal;

        return $this;
    }
}
