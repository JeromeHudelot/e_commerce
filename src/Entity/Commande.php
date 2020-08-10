<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
class Commande
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbElement;

    /**
     * @ORM\Column(type="integer")
     */
    private $weight;

    /**
     * @ORM\OneToOne(targetEntity=Client::class, mappedBy="commande", cascade={"persist", "remove"})
     */
    private $client;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity=CommandeProduct::class, mappedBy="Commande")
     */
    private $commandeProducts;

    public function __construct()
    {
        $this->products = new ArrayCollection();
		$this->price = 0;
		$this->nbElement = 0;
		$this->weight = 0;
		$this->created_at = new \Datetime();
		$this->status = false;
  $this->commandeProducts = new ArrayCollection();
		
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNbElement(): ?int
    {
        return $this->nbElement;
    }

    public function setNbElement(int $nbElement): self
    {
        $this->nbElement = $nbElement;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(Client $client): self
    {
        $this->client = $client;

        // set the owning side of the relation if necessary
        if ($client->getCommande() !== $this) {
            $client->setCommande($this);
        }

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

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

    /**
     * @return Collection|CommandeProduct[]
     */
    public function getCommandeProducts(): Collection
    {
        return $this->commandeProducts;
    }

    public function addCommandeProduct(CommandeProduct $commandeProduct): self
    {
        if (!$this->commandeProducts->contains($commandeProduct)) {
            $this->commandeProducts[] = $commandeProduct;
            $commandeProduct->setCommande($this);
        }

        return $this;
    }

    public function removeCommandeProduct(CommandeProduct $commandeProduct): self
    {
        if ($this->commandeProducts->contains($commandeProduct)) {
            $this->commandeProducts->removeElement($commandeProduct);
            // set the owning side to null (unless already changed)
            if ($commandeProduct->getCommande() === $this) {
                $commandeProduct->setCommande(null);
            }
        }

        return $this;
    }
}
