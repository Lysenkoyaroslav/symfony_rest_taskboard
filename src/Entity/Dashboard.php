<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DashboardRepository")
 */
class Dashboard
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Users", inversedBy="dashboards")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Columns", mappedBy="dashboard")
     */
    private $columns;


    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->columns = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Users[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }

    /**
     * @return Collection|Columns[]
     */
    public function getColumns(): Collection
    {
        return $this->columns;
    }

    public function addColumn(Columns $column): self
    {
        if (!$this->columns->contains($column)) {
            $this->columns[] = $column;
            $column->setDashboard($this);
        }

        return $this;
    }

    public function removeColumn(Columns $column): self
    {
        if ($this->columns->contains($column)) {
            $this->columns->removeElement($column);
            // set the owning side to null (unless already changed)
            if ($column->getDashboard() === $this) {
                $column->setDashboard(null);
            }
        }

        return $this;
    }


}
