<?php
namespace Application\Entity; 
use Doctrine\ORM\Mapping as ORM; 

/**
 * @ORM\Entity
 * @ORM\Table(name="items")
 */
class Item {
  // defining the columns of the entity (id, name, desc, dir, stack_size)

  /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue */
  private $id;

  /** @ORM\Column(type="string") */
  private $name;

  /** @ORM\Column(type="string") */
  private $description;

  /** @ORM\Column(type="string") */
  private $directions;

  /** @ORM\Column(type="integer") */
  private $stackSize;

  // getters & setters
  public function getId(): int {
    return $this->id;
  }

  public function getName(): string {
    return $this->name;
  }

  public function setName(string $name): self {
    $this->name = $name;
    return $this;
  }

  public function getDescription(): string {
    return $this->description;
  }

  public function setDescription(string $description): self {
    $this->description = $description;
    return $this;
  }

  public function getDirections(): string {
    return $this->directions;
  }

  public function setDirections(string $directions): self {
    $this->directions = $directions;
    return $this;
  }

   public function getStackSize(): int {
    return $this->stackSize;
  }

  public function setStackSize(int $stackSize): self {
    $this->stackSize = $stackSize;
    return $this;
  }

}

?>