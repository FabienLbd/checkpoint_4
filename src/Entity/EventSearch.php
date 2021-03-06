<?php


namespace App\Entity;


class EventSearch
{
    /**
     * @var string | null
     */
    private $searchText;

    /**
     * @return string|null
     */
    public function getSearchText(): ?string
    {
        return $this->searchText;
    }

    /**
     * @param string|null $searchText
     */
    public function setSearchText(?string $searchText): void
    {
        $this->searchText = $searchText;
    }
}