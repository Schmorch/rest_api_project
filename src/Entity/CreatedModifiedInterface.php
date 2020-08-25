<?php

namespace App\Entity;

/**
 * Definiert die Setter/Getter für die created und modified Eigenschaften
 */
interface CreatedModifiedInterface
{
    public function getCreated(): ?\DateTimeInterface;

    public function setCreated(\DateTimeInterface $created): self;

    public function getModified(): ?\DateTimeInterface;

    public function setModified(?\DateTimeInterface $modified): self;
}
