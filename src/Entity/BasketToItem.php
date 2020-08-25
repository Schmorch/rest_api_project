<?php

namespace App\Entity;

use App\Repository\BasketToItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="basket_to_item")
 */
class BasketToItem //implements CreatedModifiedInterface
{
    use CreatedModifiedTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Basket::class, inversedBy="basketToItemList")
     * @ORM\JoinColumn(nullable=false)
     */
    private $basket;

    /**
     * @ORM\ManyToOne(targetEntity=Item::class, inversedBy="basketToItemList")
     * @ORM\JoinColumn(nullable=false)
     */
    private $item;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBasket(): ?Basket
    {
        return $this->basket;
    }

    public function setBasket(?Basket $basket): self
    {
        $this->basket = $basket;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): self
    {
        $this->item = $item;

        return $this;
    }
}
