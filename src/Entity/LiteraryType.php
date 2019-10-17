<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class LiteraryType
 * @package App\Entity
 * @ORM\Entity()
 */
class LiteraryType
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }
}
