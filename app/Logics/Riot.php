<?php

namespace App\Logics;

use Ixudra\Curl\Facades\Curl;
use App\Models\Summoner;

class Riot
{
    public function __construct(Summoner $summoner)
    {
        $this->key = env('RIOT_API_KEY');
        $this->uri = 'https://jp.api.pvp.net/%s/?api_key='.$this->key;
        $this->summoner = $summoner;
    }

    public function getSummonerId($name)
    {
        $summoner = $this->summoner->select('summoner_id')->where('name', $name)->first();

        if ($summoner) {
            return $summoner->summoner_id;
        }

        $response = $this->curlSummonerId($name);

        if ($response === null) {
            return false;
        }

        $id = $response->{strtolower($name)}->id;
        $this->summoner->create([
            'summoner_id' => $id,
            'name' => $name
        ]);
        return $id;
    }

    public function getMatchStats($id)
    {
        $response = $this->curlMatchLists($id);
        if ($response === null) {
            return false;
        }

        $data = [];
        $data[0]['TOP'] = 0;
        $data[0]['MID'] = 0;
        $data[0]['BOTTOM'] = 0;
        $data[0]['JUNGLE'] = 0;
        foreach ($response->matches as $key => $match) {
            if (isset($data[$match->champion]) === false) {
                $data[$match->champion]['TOP'] = 0;
                $data[$match->champion]['MID'] = 0;
                $data[$match->champion]['BOTTOM'] = 0;
                $data[$match->champion]['JUNGLE'] = 0;
            }
            $data[0][$match->lane] += 1;
            $data[$match->champion][$match->lane] += 1;
        }

        $response = $this->curlStaticRanked($id);
        if ($response === null) {
            return false;
        }

        foreach ($response->champions as $static) {
            $stats = $static->stats;
            $data[$static->id]['CHAMP'] = $static->id;
            $data[$static->id]['TOTAL'] = $stats->totalSessionsPlayed;
            $data[$static->id]['WIN'] = $stats->totalSessionsWon;
            $data[$static->id]['LOSE'] = $stats->totalSessionsLost;
            $data[$static->id]['KILL'] = round($stats->totalChampionKills / $stats->totalSessionsPlayed, 1);
            $data[$static->id]['DEATH'] = round($stats->totalDeathsPerSession / $stats->totalSessionsPlayed, 1);
            $data[$static->id]['ASSIST'] = round($stats->totalAssists / $stats->totalSessionsPlayed, 1);
            $data[$static->id]['CS'] = round($stats->totalMinionKills / $stats->totalSessionsPlayed, 1);
        }

        $order = array();
        foreach ($data as $id => $value) {
            $order[$id] = $value['TOTAL'];
        }
        array_multisort($order, SORT_DESC, SORT_NUMERIC, $data);

        return $data;
    }

    public function getCurrentMatch($id)
    {
        $response = $this->curlCurrentSummoners($id);

        if ($response === null) {
            return false;
        }
        $data = [];
        foreach ($response->participants as $player) {
            $exist = $this->summoner->where('summoner_id', $player->summonerId)->exists();
            if ($exist === false) {
                $this->summoner->create([
                    'summoner_id' => $player->summonerId,
                    'name' => $player->summonerName
                ]);
            }
            $data[$player->teamId][$player->summonerId] = $player->championId;
        }
        return $data;
    }

    public function curlSummonerId($name)
    {
        $url = sprintf($this->uri, 'api/lol/jp/v1.4/summoner/by-name/'.$name);
        return json_decode(Curl::to($url)->get());
    }

    public function curlChampions()
    {
        $url = "https://global.api.pvp.net/api/lol/static-data/jp/v1.2/champion?champData=all&api_key=".$this->key;
        return json_decode(Curl::to($url)->get());
    }

    public function curlCurrentSummoners($id)
    {
        $url = sprintf(
            $this->uri,
            'observer-mode/rest/consumer/getSpectatorGameInfo/JP1/'.$id
        );
        return json_decode(Curl::to($url)->get());
    }

    public function curlMatchLists($id)
    {
        $url = sprintf($this->uri, 'api/lol/jp/v2.2/matchlist/by-summoner/'.$id).'&seasons=SEASON2016';
        return json_decode(Curl::to($url)->get());
    }

    public function curlStaticSummary($id)
    {
        $url = sprintf($this->uri, 'api/lol/jp/v1.3/stats/by-summoner/'.$id.'/summary');
        return json_decode(Curl::to($url)->get());
    }

    public function curlStaticRanked($id)
    {
        $url = sprintf($this->uri, 'api/lol/jp/v1.3/stats/by-summoner/' . $id . '/ranked');
        return json_decode(Curl::to($url)->get());
    }
}