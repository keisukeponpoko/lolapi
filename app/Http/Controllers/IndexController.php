<?php

namespace App\Http\Controllers;

use App\Models\Summoner;
use App\Models\Champion;
use App\Logics\Riot;

class IndexController extends Controller
{
    public function __construct(Summoner $summoner, Champion $champion, Riot $riot)
    {
        $this->summoner = $summoner;
        $this->champion = $champion;
        $this->riot = $riot;
    }

    public function index()
    {
        $name = 'Oltona';
        $id = $this->getSummonerId($name);

        if ($id === false) {
            return view('errors/503');
        }
        $response = $this->riot->getMatchLists($id);
        dd($response);

        $summoners = $this->riot->getCurrentSummoners($id);
        foreach ($summoners as $summoner) {
            $id = $summoner->summonerId;
            $response = $this->riot->getMatchLists($id);
            dd($response);
        }
    }
    
    public function getSummonerId($name)
    {
        $summoner = $this->summoner->select('summoner_id')->where('name', $name)->first();

        if ($summoner) {
            return $summoner->summoner_id;
        }

        $response = $this->riot->getSummonerId($name);

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
    
    public function getAllChampion() {
        $this->champion->whereNotNull('id')->delete();

        $response = $this->riot->getChampions();

        $this->champion->insertChampion($response->data);
    }
}
