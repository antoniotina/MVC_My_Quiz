<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Entity
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
    //  * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @Assert\Type("\DateTimeInterface")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $validated_at = NULL;

    /**
     * @Assert\Type("\DateTimeInterface")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $last_connection = NULL;

    /**
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
    //  * @Assert\NotBlank()
     */
    private $username;

    /**
    //  * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\History", mappedBy="user", fetch="EAGER")
     */
    private $history;

    public function __construct()
    {
        $this->roles = array('ROLE_USER');
        $this->history = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getValidatedAt()
    {
        return $this->validated_at;
    }

    public function getValidated_at()
    {
        return $this->validated_at;
    }

    public function __toString()
    {
        return $this->username;
    }

    public function getLastConnection()
    {
        return $this->last_connection;
    }

    public function getLast_connection()
    {
        return $this->last_connection;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setValidatedAt($validated_at)
    {
        $this->validated_at = $validated_at;
    }

    public function setValidated_at($validated_at)
    {
        $this->validated_at = $validated_at;
    }

    public function setLastConnection($last_connection)
    {
        $this->last_connection = $last_connection;
    }

    public function setLast_connection($last_connection)
    {
        $this->last_connection = $last_connection;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function eraseCredentials()
    {
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection|History[]
     */
    public function getHistory(): Collection
    {
        return $this->history;
    }

    public function addHistory(History $history): self
    {
        if (!$this->history->contains($history)) {
            $this->history[] = $history;
            $history->setUser($this);
        }

        return $this;
    }

    public function removeHistory(History $history): self
    {
        if ($this->history->contains($history)) {
            $this->history->removeElement($history);
            // set the owning side to null (unless already changed)
            if ($history->getUser() === $this) {
                $history->setUser(null);
            }
        }

        return $this;
    }
}
