<?php

class Perso
{
    /** @var int */
    private $id;
    /** @var string */
    private $name;
    /** @var int */
    private $hp;
    /** @var int */
    private $mana;

    /**
     * Perso constructor.
     * @param array|null $data
     */
    public function __construct(?array $data = null)
    {
        if ($data) {
            $this->hydrate($data);
        }
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getHp(): ?int
    {
        return $this->hp;
    }

    /**
     * @param int $hp
     */
    public function setHp(?int $hp): void
    {
        $this->hp = $hp;
    }

    /**
     * @return int
     */
    public function getMana(): ?int
    {
        return $this->mana;
    }

    /**
     * @param int $mana
     */
    public function setMana(?int $mana): void
    {
        $this->mana = $mana;
    }


    public function hydrate(array $data)
    {
        foreach ($data as $key => $value) {
            $method = 'set'.ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    public function dehydrate(): array
    {
        $data = get_object_vars($this);
        unset($data["id"]); // Beark;
        return $data;
    }


}
