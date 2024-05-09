<?php

namespace App\Dtos\ABibliaDigitalService;

use App\Dtos\Dto;

class GetLivrosDto extends Dto
{
    public string $ptAbbrev;
    public string $enAbbrev;
    public string $author;
    public string $chapters;
    public string $group;
    public string $name;
    public string $testament;
}