<?php

namespace App\Logics;

use Ixudra\Curl\Facades\Curl;

class Riot
{
    public function __construct()
    {
        $this->key = env('RIOT_API_KEY');
        $this->uri = 'https://jp.api.pvp.net/%s/?api_key='.$this->key;
    }

    public function getSummonerId($name)
    {
        $url = sprintf($this->uri, 'api/lol/jp/v1.4/summoner/by-name/'.$name);
        return json_decode(Curl::to($url)->get());
    }

    public function getChampions()
    {
        $url = "https://global.api.pvp.net/api/lol/static-data/jp/v1.2/champion?champData=all&api_key=".$this->key;
        return json_decode(Curl::to($url)->get());
    }

    public function getCurrentSummoners($id)
    {
        $url = sprintf(
            $this->uri,
            'observer-mode/rest/consumer/getSpectatorGameInfo/JP1/'.$id
        );
        $response = json_decode(Curl::to($url)->get());
        return $response->participants;
    }

    public function getMatchLists($id)
    {
        $url = sprintf($this->uri, 'api/lol/jp/v2.2/matchlist/by-summoner/'.$id);
        return json_decode(Curl::to($url)->get());
    }
}