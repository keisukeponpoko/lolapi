<?php

namespace App\Http\Controllers;

use App\Models\Summoner;

class IndexController extends Controller
{
    public function __construct(Summoner $summoner)
    {
        $key = env('RIOT_API_KEY');
        $this->uri = 'https://jp.api.pvp.net/api/lol/jp/%s/?api_key='.$key;

        $this->summoner = $summoner;
    }

    public function index()
    {
        $name = 'pokopok';
        $id = $this->getSummoner($name);

        if ($id === false) {
            return view('errors/503');
        }

        //$url = sprintf($this->uri, 'v2.2/matchlist/by-summoner/'.$id);
        $url = sprintf($this->uri, 'v1.3/game/by-summoner/'.$id.'/recent');
        $response = json_decode(\Curl::to($url)->get());

        dd($response);
    }
    
    public function getSummoner($name)
    {
        $summoner = $this->summoner->select('id')->where('name', $name)->first();
        
        if ($summoner) {
            return $summoner->id;
        }

        $url = sprintf($this->uri, 'v1.4/summoner/by-name/'.$name);
        $response = json_decode(\Curl::to($url)->get());

        if ($response === null) {
            return false;
        }

        $id = $response->{$name}->id;

        $this->summoner->id = $id;
        $this->summoner->name = $name;
        $this->summoner->save();
        return $id;
    }
}
