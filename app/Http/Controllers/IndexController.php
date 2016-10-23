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
        $id = request()->cookie('id');

        $stats = [];
        //対戦相手一覧を取得
        $match = $this->riot->getCurrentMatch($id);

        if ($match === false) {
            return redirect('stats');
        }
        //tier 取得　https://jp.api.pvp.net/api/lol/jp/v2.5/league/by-summoner/6346544/entry?api_key=d21e7e82-9bf6-4d9f-896d-c1e806ae8a0c
        //masterとchallengerの人のbuildとかまとめる。lolkingみたいに

        $summonerIds = array_merge(array_keys($match[100]), array_keys($match[200]));
        $championIds = array_merge($match[100], $match[200]);

        $summoners = $this->summoner->select('summoner_id', 'name')
            ->whereIn('summoner_id', $summonerIds)
            ->lists('name', 'summoner_id');
        $champions = $this->champion->whereIn('champion_id', $championIds)->get();
        foreach ($champions as $value) {
            $champion[$value->champion_Id] = $value;
        }

        return view('match')
            ->with('match', $match)
            ->with('champions', $champion)
            ->with('summoners', $summoners);
    }

    public function getSearch()
    {
        return view('search');
    }

    public function postSearch()
    {
        $name = request()->input('name');
        $id = $this->riot->getSummonerId($name);

        if ($id === false) {
            return redirect('search')
                ->withErrors('no Summoner Name')
                ->withInput();
        }

        $cookie = cookie()->forever('id', $id);

        return redirect('/')->withCookie($cookie);
    }

    public function stats($id = '')
    {
        if (empty($id)) {
            $id = request()->cookie('id');
        }
        $stats = $this->riot->getMatchStats($id);

        if ($stats === false) {
            return redirect('search')
                ->withErrors('api limit over')
                ->withInput();
        }

        $summoners = $this->summoner->select('summoner_id', 'name')
            ->where('summoner_id', $id)
            ->lists('name', 'summoner_id')->toArray();
        $champions = $this->champion->select('champion_id', 'name')->lists('name', 'champion_id')->toArray();
        $champions[0] = '合計';

        return view('stats')
            ->with('id', $id)
            ->with('stats', $stats)
            ->with('champions', $champions)
            ->with('summoners', $summoners);
    }

    public function champion()
    {
        $champions = $this->champion->get();

        return view('champion')
            ->with('champions', $champions);
    }
    
    public function getAllChampion() {
        $this->champion->whereNotNull('id')->delete();

        $response = $this->riot->curlChampions();

        $this->champion->insertChampion($response->data);

        print 'ok';
    }
}
