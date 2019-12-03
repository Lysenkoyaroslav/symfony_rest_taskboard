<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 */
class Users
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    private $userName;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
    private $apiToken;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Roles", inversedBy="users")
     * @ORM\Column(type="integer", name="roles_id", options={"unsigned":true, "default":0})
     */
    private $roles=0;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Dashboard", mappedBy="users")
     *
     */
    private $dashboards;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Status", inversedBy="users")
     *
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $temporaryToken;


    public function __construct()
    {
        $this->dashboards = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(?string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    public function getRoles(): ?Roles
    {
        return $this->roles;
    }

    public function setRoles(?Roles $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection|Dashboard[]
     */
    public function getDashboards(): Collection
    {
        return $this->dashboards;
    }

    public function addDashboard(Dashboard $dashboard): self
    {
        if (!$this->dashboards->contains($dashboard)) {
            $this->dashboards[] = $dashboard;
            $dashboard->addUser($this);
        }

        return $this;
    }

    public function removeDashboard(Dashboard $dashboard): self
    {
        if ($this->dashboards->contains($dashboard)) {
            $this->dashboards->removeElement($dashboard);
            $dashboard->removeUser($this);
        }

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTemporaryToken(): ?string
    {
        return $this->temporaryToken;
    }

    public function setTemporaryToken(?string $temporaryToken): self
    {
        $this->temporaryToken = $temporaryToken;

        return $this;
    }


}
