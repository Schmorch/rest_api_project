<?php

namespace App\Entity;

use App\Repository\BasketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="basket")
 */
class Basket //implements CreatedModifiedInterface
{
    use CreatedModifiedTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=BasketToItem::class, mappedBy="basket", fetch="EXTRA_LAZY")
     */
    private $basketToItemList;

    public function __construct()
    {
        $this->basketToItemList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|BasketToItem[]
     */
    public function getBasketToItemList(): Collection
    {
        return $this->basketToItemList;
    }
}
