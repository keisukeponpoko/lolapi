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
                'spells_q' =>  $this->getSpellTooltip($champ->spells[0]),
                'spells_w' =>  $this->getSpellTooltip($champ->spells[1]),
                'spells_e' =>  $this->getSpellTooltip($champ->spells[2]),
                'spells_r' =>  $this->getSpellTooltip($champ->spells[3])
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
                $replace[$matchE[0][$key]] = implode('/', $spell->effect[$value]);
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
