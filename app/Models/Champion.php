<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Champion extends Model
{
    protected $guarded = ['id'];

    public function insertChampion($data)
    {
        foreach ($data as $champ) {
            $this->create([
                'champion_Id' =>  $champ->id,
                'key' =>  $champ->key,
                'name' =>  $champ->name,
                'allytips' =>  implode(' ', $champ->allytips),
                'enemytips' =>  implode(' ', $champ->enemytips),
                'blurb' =>  $champ->blurb,
                'passive' =>  $champ->passive->sanitizedDescription,
                'spells_q' =>  $champ->spells[0]->sanitizedDescription,
                'spells_w' =>  $champ->spells[1]->sanitizedDescription,
                'spells_e' =>  $champ->spells[2]->sanitizedDescription,
                'spells_r' =>  $champ->spells[3]->sanitizedDescription
            ]);
        }
    }

    public function getSpellTooltip($spell) {
        $tooltip = $spell->sanitizedTooltip;

        //文章中から{{ e. }}を抜き出して、置き換えるデータを取得
        preg_match_all('/\{\{\se([0-9]*?)\s\}\}/', $tooltip, $matchE);

        $replace = [];
        foreach ($matchE[1] as $key => $value) {
            if (isset($replace[$matchE[0][$key]]) === false) {
                $replace[$matchE[0][$key]] = $spell->effectBurn[$value];
            }
        }

        //文章中から{{ a. }}を抜き出す
        preg_match_all('/\{\{\sa([0-9]*?)\s\}\}/', $tooltip, $matchA);

        foreach ($matchA[1] as $key => $value) {
            if (isset($replace[$matchA[0][$key]]) === false) {
                $replace[$matchA[0][$key]] = implode('/', $spell->vars[$key]->coeff);
            }
        }

        $tooltip = strtr($tooltip, $replace);
        return $tooltip;
    }
}
