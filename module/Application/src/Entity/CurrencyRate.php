<?php
namespace Application\Entity; 
use Doctrine\ORM\Mapping as ORM; 

/**
 * @ORM\Entity
 * @ORM\Table(name="currency_rates")
 */
class CurrencyRate{
    // defining the columns of the entity (id, currency, value, time)

    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue */
    private $id;

    /** @ORM\Column(type="string") */
    private $currency;

    /** @ORM\Column(type="float") */
    private $value;

    /** @ORM\Column(type="datetime") */
    private $fetchedAt;

    /** @ORM\Column(type="string") */
    private $icon;

    /** @ORM\Column(type="integer") */
    private $listingCount;

    /** @ORM\Column(type="string") */
    private $league;

    // getters & setters
    public function getId(): int {
        return $this->id;
    }

    public function getCurrency(): string {
        return $this->currency;
    }

    public function setCurrency(string $currency): self {
        $this->currency = $currency;
        return $this;
    }

    public function getValue(): float {
        return $this->value;
    }

    public function setValue(float $value): self {
        $this->value = $value;
        return $this;
    }

    public function getFetchedAt(): \DateTime {
        return $this->fetchedAt;
    }

    public function setFetchedAt(\DateTime $fetchedAt): self {
        $this->fetchedAt = $fetchedAt;
        return $this;
    }

    public function getIcon(): string {
        return $this->icon;

    }

    public function setIcon(string $icon): self {
        $this->icon = $icon;
        return $this;
    }

    public function getListingCount(): int {
        return $this->listingCount;
    }

    public function setListingCount(int $listingCount): self {
        $this->listingCount = $listingCount;
        return $this;
    }

    public function getLeague(): string {
        return $this->league;
    }
    public function setLeague(string $league): self {
        $this->league = $league;
        return $this;
    }
}

?>