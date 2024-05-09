<?php

namespace App\Http\Controllers;

use App\Http\Services\ABibliaDigitalService;
use App\Http\Services\Dtos\ABibliaDigitalService\GetLivrosDto;
use Illuminate\Http\Response;

class LivrosController extends Controller
{
    public function index(): Response
    {
        /** @var GetLivrosDto */
        $livros = app(ABibliaDigitalService::class)->getLivros();

        return response($livros);
    }
}
