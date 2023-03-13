<?php namespace App\Contracts;

use App\Models\Coincidence;
use App\Models\MatchSearch;
use Illuminate\Support\Collection;

interface MatchSearchService
{

    /**
     * Obtener personas que coincidan con el nombre sumistrado
     * @param int $id
     * @param array $dto
     * $dto = [
     *      'name',                         String
     *      'match_percentage',             Double
     * ]
     *
     * @return MatchSearch
     */
    public function getMatchNames(int $id, array $dto): MatchSearch;

    /**
     * Obtener personas por uuid de consulta previa
     * @param int $uuid
     * @return MatchSearch
     */
    public function getMatchNamesByUUID(int $uuid): MatchSearch;

}
