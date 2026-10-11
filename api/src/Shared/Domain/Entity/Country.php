<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\Entity;

use DateTimeImmutable;
use Movioza\Shared\Domain\ValueObject\CountryID;
use Movioza\Shared\Domain\ValueObject\CountryName;
use Movioza\Shared\Domain\ValueObject\CountryCode;

/**
 * Represents a country with its identifier, name, and code.
 */
class Country
{
    private ?DateTimeImmutable $updatedAt = null;

    public function __construct(
        private readonly CountryID $id,
        private CountryName $name,
        private CountryCode $code,
        private readonly DateTimeImmutable $createdAt,
    ) {
    }

    /**
     * Returns the country's identifier.
     */
    public function getId(): CountryID
    {
        return $this->id;
    }

    /**
     * Returns the country's name.
     */
    public function getName(): CountryName
    {
        return $this->name;
    }

    /**
     * Returns the country's code.
     */
    public function getCode(): CountryCode
    {
        return $this->code;
    }

    /**
     * Returns the country's creation date.
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Returns the country's update date.
     */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Renames the country and records the supplied change time.
     *
     * Does nothing if the name is unchanged.
     */
    public function rename(CountryName $name, DateTimeImmutable $changedAt): void
    {
        if ($this->name->equals($name)) {
            return;
        }

        $this->name = $name;
        $this->updatedAt = $changedAt;
    }

    /**
     * Changes the country's code and records the supplied change time.
     *
     * Does nothing if the code is unchanged.
     */
    public function changeCode(CountryCode $code, DateTimeImmutable $changedAt): void
    {
        if ($this->code->equals($code)) {
            return;
        }

        $this->code = $code;
        $this->updatedAt = $changedAt;
    }
}
