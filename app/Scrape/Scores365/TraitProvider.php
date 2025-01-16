<?php

namespace App\Scrape\Scores365;

trait TraitProvider
{
    protected int $appTypeId;
    protected string $timezoneName;
    protected int $sports;
    protected int $userCountryId;

    protected int $langId;

    /**
     * @return mixed
     */
    public function getAppTypeId()
    {
        return $this->appTypeId;
    }

    /**
     * @param mixed $appTypeId
     */
    public function setAppTypeId($appTypeId): void
    {
        $this->appTypeId = $appTypeId;
    }

    public function setTimeZone($timezoneName): void
    {
        $this->timezoneName = $timezoneName;
    }

    public function getSports(): int
    {
        return $this->sports;
    }

    public function setSports(int $sports): void
    {
        $this->sports = $sports;
    }

    public function getUserCountryId(): int
    {
        return $this->userCountryId;
    }

    public function setUserCountryId(int $userCountryId): void
    {
        $this->userCountryId = $userCountryId;
    }

    public function getLangId(): int
    {
        return $this->langId;
    }

    public function setLangId(int $langId): void
    {
        $this->langId = $langId;
    }
}
