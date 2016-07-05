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
        $summoners = $this->riot->curlCurrentSummoners($id);

        if ($summoners === false) {
            return redirect('personal')->with('id', $id);
        }

        foreach ($summoners as $summoner) {
            dd($summoner);
            //今までのrankマッチの対戦成績を取得。
            $stats[$id] = $this->riot->getMatchStats($id);
        }

        $summoners = $this->summoner->select('summoner_id', 'name')->lists('name', 'summoner_id')->toArray();
        $champions = $this->champion->select('champion_id', 'name')->lists('name', 'champion_id')->toArray();
        $champions[0] = '合計';

        return view('stats')
            ->with('stats', $stats)
            ->with('champions', $champions)
            ->with('summoners', $summoners);
    }

    public function getSearch()
    {
        return view('login');
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

    public function personal()
    {
        $id = request()->cookie('id');
        $stats = $this->riot->getMatchStats($id);

        $summoners = $this->summoner->select('summoner_id', 'name')->lists('name', 'summoner_id')->toArray();
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
