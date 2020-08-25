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
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="basket", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=BasketToItem::class, mappedBy="basket")
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
     * @return Collection|BasketToItem[]
     */
    public function getBasketToItemList(): Collection
    {
        return $this->basketToItemList;
    }

    // public function addItem(BasketToItem $basketToItem): self
    // {
    //     if (!$this->basketToItemList->contains($basketToItem)) {
    //         $this->basketToItemList[] = $basketToItem;
    //         $basketToItem->setBasket($this);
    //     }

    //     return $this;
    // }

    // public function removeBasketToItemList(BasketToItem $basketToItem): self
    // {
    //     if ($this->basketToItemList->contains($basketToItem)) {
    //         $this->basketToItemList->removeElement($basketToItem);
    //         // set the owning side to null (unless already changed)
    //         if ($basketToItem->getBasket() === $this) {
    //             $basketToItem->setBasket(null);
    //         }
    //     }

    //     return $this;
    // }
}
