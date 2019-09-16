<?php

namespace Tfts\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="tftsRegistrations",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="user_id", columns={"user_id","team_id","game_id","lan_id"})}
 * )
 */
class Registration
{
    /**
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $rnd_number;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Tfts\Entity\Game", inversedBy="registrations")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="game_id", nullable=false)
     */
    private $game;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Tfts\Entity\Team", inversedBy="registrations")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="team_id", nullable=false)
     */
    private $team;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Tfts\Entity\Lan", inversedBy="registrations")
     * @ORM\JoinColumn(name="lan_id", referencedColumnName="lan_id", nullable=false)
     */
    private $lan;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Concrete\Core\Entity\User\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="uID", nullable=false)
     */
    private $user;
}