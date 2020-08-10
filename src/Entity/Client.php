<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppAssert;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 */
class Client
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlanks
	 * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Votre prénom ne peu pas contenir de chiffre"
     * )
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank
	 * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Votre nom ne peu pas contenir de chiffre"
     * )
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank
     */
    private $adress;

    /**
     * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank
	 * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Votre ville ne peu pas contenir de chiffre"
     * )
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank
	 * @Assert\Regex(
     *     pattern="/\d/")
     * )
     */
    private $postal_code;

    /**
     * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank
	 * @AppAssert\Telephone()
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank
	 * @Assert\Email(
     *     message = "L'adresse email fourni n'est pas valide."
     * )
     */
    private $email;

    /**
     * @ORM\OneToOne(targetEntity=Commande::class, inversedBy="client", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $commande;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(string $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }
}
