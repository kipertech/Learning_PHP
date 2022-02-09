<?php

class Pokemon
{
    public int $id;
    public string $name;
    public string $evolution;

    public function __construct(int $id, string $name, string $evolution)
    {
        $this->id = $id;
        $this->name = $name;
        $this->evolution = $evolution;
    }
}

function getPokemonList()
{
    $pokemon_list = [];

    array_push($pokemon_list,
        new Pokemon(
            1,
            'Charmander',
            'Charmeleon'
        ),
        new Pokemon(
            2,
            'Squirtle',
            'Wartortle'
        ),
        new Pokemon(
            3,
            'Caterpie',
            'Metapod'
        ),
        new Pokemon(
            4,
            'Weedle',
            'Kakuna'
        ),
        new Pokemon(
            5,
            'Bulbasaur',
            'Ivysaur'
        ),
        new Pokemon(
            6,
            'Spearow',
            'Fearow'
        ),
        new Pokemon(
            7,
            'Sandshrew',
            'Sandslash'
        )
    );

    return($pokemon_list);
}