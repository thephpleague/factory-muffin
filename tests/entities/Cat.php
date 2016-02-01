<?php
namespace League\FactoryMuffin\Test;

/**
 * @Entity
 * @Table(name="cats")
 */
class Cat
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /** @Column(length=140) */
    private $name;

    /**
     * @ManyToOne(targetEntity="League\FactoryMuffin\Test\User", inversedBy="cats")
     */
    private $user;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

}