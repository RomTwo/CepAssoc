<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\AccountRepository")
 */
class Account implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="Veuiller remplir le champ nom")
     * @Assert\Regex(
     *     pattern = "/^[a-zA-ZÀ-ú\-\s]*$/",
     *     match = true,
     *     message = "le nom n'est pas correct"
     * )
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="Veuiller remplir le champ prénom")
     * @Assert\Regex(
     *     pattern = "/^[a-zA-ZÀ-ú\-\s]*$/",
     *     match = true,
     *     message = "le prénom n'est pas correct"
     * )
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=1)
     * @Assert\NotNull(message="Veuiller remplir le champ sexe")
     * @Assert\Regex(
     *     pattern = "/^M|F$/",
     *     match = true,
     *     message = "le sexe n'est pas correct"
     * )
     */
    private $sex;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotNull(message="Veuiller remplir le champ date de naissance")
     * message = "la date de naissance n'est pas correct"
     */
    private $birthDate;

    /**
     * @ORM\Column(type="string", length=5)
     * @Assert\NotNull(message="Veuiller remplir le champ code postal")
     * @Assert\Regex(
     *     pattern = "/^([0-9]{2}|(2A)|2B)[[0-9]{3}$/",
     *     match = true,
     *     message = "le code postal n'est pas correct"
     * )
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="Veuiller remplir le champ adresse")
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="Veuiller remplir le champ email")
     * @Assert\Email(message = "l'adresse mail n'est pas correct")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="Veuiller remplir le champ mot de passe")
     * @Assert\Regex(
     *     pattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{8,15})$/",
     *     match = true,
     *     message = "le mot de passe n'est pas correct"
     * )
     */
    private $password;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Adherent", inversedBy="parents", cascade={"persist"})
     * @Assert\Valid()
     */
    private $children;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EventManagement", mappedBy="account")
     */
    private $eventManagements;

    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $tokenForgetPass;

    /**
     * @Assert\Type("boolean")
     */
    private $addAccountAdherent;

    /**
     * @Assert\Type("boolean")
     */
    private $validateHealthQuestionnaire;

    /**
     * Account constructor.
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->eventManagements = new ArrayCollection();
        $this->roles = array('ROLE_MODERATOR');
    }


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

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

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

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }


    /**
     * @return Collection|Adherent[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(Adherent $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
        }

        return $this;
    }

    public function removeChild(Adherent $child): self
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
        }

        return $this;
    }

    /**
     * @return Collection|EventManagement[]
     */
    public function getEventManagements(): Collection
    {
        return $this->eventManagements;
    }

    public function addEventManagement(EventManagement $eventManagement): self
    {
        if (!$this->eventManagements->contains($eventManagement)) {
            $this->eventManagements[] = $eventManagement;
            $eventManagement->setAccount($this);
        }

        return $this;
    }

    public function removeEventManagement(EventManagement $eventManagement): self
    {
        if ($this->eventManagements->contains($eventManagement)) {
            $this->eventManagements->removeElement($eventManagement);
            // set the owning side to null (unless already changed)
            if ($eventManagement->getAccount() === $this) {
                $eventManagement->setAccount(null);
            }
        }

        return $this;
    }

    public function getTokenForgetPass(): ?string
    {
        return $this->tokenForgetPass;
    }

    public function setTokenForgetPass($tokenForgetPass)
    {
        $this->tokenForgetPass = $tokenForgetPass;
    }


    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        if ($this->roles[0] === "ROLE_SUPER_ADMIN") {
            return ['ROLE_SUPER_ADMIN'];
        } elseif ($this->roles[0] === "ROLE_ADMIN") {
            return ['ROLE_ADMIN'];
        } elseif ($this->roles[0] === "ROLE_MODERATOR") {
            return ['ROLE_MODERATOR'];
        } elseif ($this->roles[0] === "ROLE_USER") {
            return ['ROLE_USER'];
        }
        return [];
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }


    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->getEmail();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return mixed
     */
    public function getAddAccountAdherent()
    {
        return $this->addAccountAdherent;
    }

    /**
     * @param mixed $addAccountAdherent
     */
    public function setAddAccountAdherent($addAccountAdherent): void
    {
        $this->addAccountAdherent = $addAccountAdherent;
    }

    /**
     * @return mixed
     */
    public function getValidateHealthQuestionnaire()
    {
        return $this->validateHealthQuestionnaire;
    }

    /**
     * @param mixed $validateHealthQuestionnaire
     */
    public function setValidateHealthQuestionnaire($validateHealthQuestionnaire): void
    {
        $this->validateHealthQuestionnaire = $validateHealthQuestionnaire;
    }



}
