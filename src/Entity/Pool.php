<?php

namespace Tfts\Entity;

use Concrete\Core\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Tfts\Entity\Game;
use Tfts\Entity\PoolUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="tftsPools")
 */
class Pool {

  /**
   * @ORM\Id
   * @ORM\Column(type="integer", length=10)
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $pool_id;

  /**
   * @ORM\Column(type="string", length=50, nullable=false)
   */
  private $pool_name;

  /**
   * @ORM\Column(type="integer", length=1, nullable=false, options={"default":0})
   */
  private $pool_is_played = 0;

  /**
   * @ORM\ManyToOne(targetEntity="Tfts\Entity\Game", inversedBy="pools")
   * @ORM\JoinColumn(name="game_id", referencedColumnName="game_id", nullable=false)
   */
  private $game;

  /**
   * @ORM\ManyToOne(targetEntity="Concrete\Core\Entity\User\User")
   * @ORM\JoinColumn(name="host_id", referencedColumnName="uID", nullable=false)
   */
  private $host;

  /**
   * @ORM\OneToMany(targetEntity="Tfts\Entity\PoolUser", mappedBy="pool")
   */
  private $users;

  /**
   * @ORM\ManyToMany(targetEntity="Tfts\Entity\Pool", mappedBy="children")
   */
  private $parents;

  /**
   * @ORM\ManyToMany(targetEntity="Tfts\Entity\Pool", inversedBy="parents")
   * @ORM\JoinTable(
   *     name="tftsPoolParentChild",
   *     joinColumns={@ORM\JoinColumn(name="child_id", referencedColumnName="pool_id", nullable=false)},
   *     inverseJoinColumns={@ORM\JoinColumn(name="parent_id", referencedColumnName="pool_id", nullable=false)}
   * )
   */
  private $children;

  public function __construct(String $name) {
    $this->pool_name = $name;

    $this->users = new ArrayCollection();
    $this->parents = new ArrayCollection();
    $this->children = new ArrayCollection();
  }

  public function getId(): int {
    return $this->pool_id;
  }

  public function getName(): String {
    return $this->pool_name;
  }

  public function isPlayed(): bool {
    return $this->pool_is_played == 1;
  }

  public function getGame(): ?Game {
    return $this->game;
  }

  public function setGame(Game $game) {
    $this->game = $game;
  }

  public function getHost(): ?User {
    return $this->host;
  }

  public function setHost(User $host) {
    $this->host = $host;
  }

  public function addUser(PoolUser $user) {
    $this->users->add($user);
    $user->setPool($this);
  }

  public function addParent(Pool $parent) {
    $this->parents->add($parent);
    $parent->addChild($this);
  }

  public function addChild(Pool $child) {
    $this->children->add($child);
    $child->addParent($this);
  }

  public function getUsers(): Collection {
    return $this->users;
  }

  public function getParents(): Collection {
    return $this->parents;
  }

  public function getChildren(): Collection {
    return $this->children;
  }

}
