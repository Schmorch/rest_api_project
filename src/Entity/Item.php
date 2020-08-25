<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="item")
 */
class Item //implements CreatedModifiedInterface
{
    use CreatedModifiedTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=BasketToItem::class, mappedBy="item")
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|BasketToItem[]
     */
    public function getBasketToItemList(): Collection
    {
        return $this->basketToItemList;
    }

    // public function addBasketToItemList(BasketToItem $basketToItem): self
    // {
    //     if (!$this->basketToItemList->contains($basketToItem)) {
    //         $this->basketToItemList[] = $basketToItem;
    //         $basketToItem->setItem($this);
    //     }

    //     return $this;
    // }

    // public function removeBasketToItemList(BasketToItem $basketToItem): self
    // {
    //     if ($this->basketToItemList->contains($basketToItem)) {
    //         $this->basketToItemList->removeElement($basketToItem);
    //         // set the owning side to null (unless already changed)
    //         if ($basketToItem->getItem() === $this) {
    //             $basketToItem->setItem(null);
    //         }
    //     }

    //     return $this;
    // }
}
