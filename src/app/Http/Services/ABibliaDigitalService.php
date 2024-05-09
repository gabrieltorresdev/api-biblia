<?php

namespace App\Http\Services;

use App\Exceptions\ErroABibliaDigitalServiceException;
use App\Dtos\ABibliaDigitalService\GetLivrosDto;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class ABibliaDigitalService
{
    /**
     * Busca os livros da bíblia
     *
     * @return array<GetLivrosDto>
     */
    public function getLivros(): array
    {
        $client = $this->newClient();

        $resposta = $client->get('books');

        if ($resposta->failed()) {
            throw new ErroABibliaDigitalServiceException;
        }

        $livros = $resposta->json();

        return GetLivrosDto::arrayOf(array_map(function ($livro) {
            return [
                'ptAbbrev' => $livro['abbrev']['pt'],
                'enAbbrev' => $livro['abbrev']['en'],
                'author' => $livro['author'],
                'chapters' => $livro['chapters'],
                'group' => $livro['group'],
                'name' => $livro['name'],
                'testament' => $livro['testament'],
            ];
        }, $livros));
    }

    protected function newClient(): PendingRequest
    {
        return Http::baseUrl(config('services.abibliadigital.url'))
                   ->withToken(config('services.abibliadigital.key'));
    }
}