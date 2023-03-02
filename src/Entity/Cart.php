<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CartRepository::class)
 */
class Cart
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="carts")
     */
    private $proCart;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="carts")
     */
    private $userCart;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProCart(): ?Product
    {
        return $this->proCart;
    }

    public function setProCart(?Product $proCart): self
    {
        $this->proCart = $proCart;

        return $this;
    }

    public function getUserCart(): ?User
    {
        return $this->userCart;
    }

    public function setUserCart(?User $userCart): self
    {
        $this->userCart = $userCart;

        return $this;
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
    
}
