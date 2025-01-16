<?php

namespace App\Scrape\Scores365;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Scrape365
{
    use TraitProvider;
    protected $url;
    protected $imageUrl;

    /**
     * Initializes the Scrape365.
     *
     * @return void
     */
    public function __construct()
    {
        $this->url = "https://webws.365scores.com/web";
        $this->imageUrl = "https://imagecache.365scores.com";
        $this->appTypeId = 5;
        $this->timezoneName = 'Asia/Riyadh';
        $this->sports = 1;
        $this->userCountryId = 131;
        // lang :  { 1 : english , 27 : arabic }
        $this->langId = 1;
    }

    public function setLangId($langId)
    {
        $this->langId =$langId;
    }
    public function getDomainImageUrl()
    {
        return $this->imageUrl;
    }
    /**
     * @throws GuzzleException
     */
    protected function getReq($link, $headers = [],$query = [],$withSports = true)
    {
        $addHeaders = [
            'Origin'  => 'https://www.365scores.com',
            'Referer' => 'https://www.365scores.com',
            'Authority' => 'webws.365scores.com',
            'User-Agent'          => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
        ];
        $addQuery = [
            'appTypeId' => $this->appTypeId,
            'timezoneName' => $this->timezoneName,
            'userCountryId' => $this->userCountryId,
            'langId' => $this->langId,
        ];
        if($withSports) {
            $addQuery['sports'] = $this->sports; // 1 is football
        }
        $query = array_merge($addQuery,$query);
        $headers = array_merge($addHeaders,$headers);

        $client = new Client();
        $response = $client->get($link, [
            'headers' => $headers,
            'query'  => $query,
            'timeout' => 10, // Set a timeout for each request
            'http_errors' => false, // Prevent Guzzle from throwing exceptions on 4xx or 5xx responses
            'verify' => false, // Disable SSL verification (optional, but recommended for testing)
        ]);
        $body = (string) $response->getBody();

        $data = new \stdClass();
        $data->body = json_decode(json: $body, associative: false);
        $data->status = $response->getStatusCode();
        $data->query = $query;
        $data->fullUrl = $link . '?' . http_build_query($query);

        return $data;
    }

    public function getTeams()
    {
        $query = [
            'limit' =>9000,
            'withSeasons' => true,
        ];

        return $this->getReq(
            link:  $this->url.'/competitors/top/',
            query: $query
        );
    }

    // https://webws.365scores.com/web/competitors/?appTypeId=5&langId=27&timezoneName=Africa/Cairo&userCountryId=131&competitors=8201&withSeasons=true
    public function getTeam($id)
    {
        $query = [
            'competitors' => $id,
            'withSeasons' => true,
        ];

        return $this->getReq(
            link:  $this->url.'/competitors/',
            query: $query
        );
    }
    public function getLeagues()
    {
        $query = [
            'limit' =>9000,
            'withSeasons' => true,
        ];

        return $this->getReq(
            link:  $this->url.'/competitions/top/',
            query: $query
        );
    }

    //https://webws.365scores.com/web/competitions/?appTypeId=5&langId=27&timezoneName=Africa/Cairo&userCountryId=131&competitions=552&withSeasons=true&withBestOdds=true
    public function getLeague($id)
    {
        $query = [
            'competitions' => $id,
            'withSeasons' => true,
            'withBestOdds' => true,
        ];

        return $this->getReq(
            link:  $this->url.'/competitions/',
            query: $query,
            withSports: false
        );
    }

    ///web/games/allscores/?appTypeId=5&langId=27&timezoneName=Africa/Cairo&userCountryId=131&sports=1&startDate=14/07/2024&endDate=14/07/2024&onlyMajorGames=true&withTop=true&onlyLiveGames=true
    public function getMatches($startDate,$endDate,$isLive = false){
        $query = [
//            'limit' =>9000,
//            'withSeasons' => true,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'showOdds' => 'true',
            'onlyMajorGames' => 'true',
            'withTop' => 'true',
            'onlyLiveGames' => $isLive ? 'true' : 'false'
        ];

        return $this->getReq(
            link:  $this->url.'/games/allscores/',
            query: $query
        );
    }

    //https://webws.365scores.com/web/game/?appTypeId=5&langId=27&timezoneName=Africa/Cairo&userCountryId=131&gameId=4026750
    public function getMatchDetails($id){
        $query = [
            'gameId' => $id,
        ];

        return $this->getReq(
            link:  $this->url.'/game/',
            query: $query
        );
    }
    //stats
    //https://webws.365scores.com/web/game/stats/?appTypeId=5&langId=27&timezoneName=Africa/Cairo&userCountryId=131&games=4026750
    public function getMatchStatistics($id){
        $query = [
            'games' => $id,
        ];

        return $this->getReq(
            link:  $this->url.'/game/stats/',
            query: $query
        );
    }
}
