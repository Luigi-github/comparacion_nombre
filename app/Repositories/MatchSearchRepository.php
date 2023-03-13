<?php namespace App\Repositories;

use App\Contracts\MatchSearchService;
use App\Models\Coincidence;
use App\Models\MatchSearch;
use App\Models\People;

class MatchSearchRepository implements MatchSearchService
{
    public function getMatchNames(int $id, array $dto): MatchSearch
    {
        // Obtener el listado de personas
        $people = People::get();

        // Consultar coincidencias
        $coincidences = [];
        foreach ($people as $person){
            // Normalizar nombress
            $name1 = $this->normalizeName($dto['name']);
            $name2 = $this->normalizeName($person->name);

            // Carcular la similitud Jaro-Winkler
            $jaroWinklerSimilarity = $this->jaroWinkler($name1, $name2);

            // En caso de que cumpla con el porcentaje de coincidencia,
            // registrar en los resultados de busqueda
            $matchPercentage = $jaroWinklerSimilarity * 100;
            if($matchPercentage >= $dto['match_percentage']){
                $coincidence = $person->toArray();
                unset($coincidence['id']);
                $coincidence['match_search_id'] = $id;
                $coincidence['match_percentage'] = $matchPercentage;
                $coincidences[] = $coincidence;
            }
        }

        // Registrar coincidencias
        $chunks = collect($coincidences)->chunk(100);
        foreach ($chunks as $chunk){
            Coincidence::insert($chunk->toArray());
        }

        // Se retornan todas las coincidencias encontradas en la busqueda
        return $this->getMatchNamesByUUID($id);
    }

    public function getMatchNamesByUUID(int $uuid): MatchSearch
    {
        return MatchSearch::with(['coincidences'])->whereId($uuid)->first();
    }

    private function normalizeName(string $name): string
    {
        // Eliminar espacios en los extremos y convertir a minusculas
        $name = trim(mb_strtolower($name));

        // Reemplazar caracteres con su equivalente sin acentos
        $name = str_replace(
            array('á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ'),
            array('a', 'e', 'i', 'o', 'u', 'u', 'n'),
            $name
        );

        // Limpiar caracteres especiales
        return preg_replace('/[^\p{L}\s]/u', '', $name);
    }

    private function jaroWinkler(string $name1, string $name2, float $p = 0.1): float
    {
        // Calcular distancia maxima de coincidencia para determinar si los
        // nombres son iguales, se toma la distanica más larga multiplicado por
        // le coeficiente de ajuste, recomendado por defecto en 0.1
        $maxLen = max(strlen($name1), strlen($name2));
        $matchDistance = (int) floor($maxLen * $p);

        // Se marcan los caracteres que coinciden en ambos nombres,
        // se inician dos matrices en false, si un caracter del primer nombre
        // coincide con un caracter del segundo nombre se marca en las matrices.
        // También se calcula la cantidad de caracteres coincidentes
        $matches = 0;
        $name1Matches = array_fill(0, strlen($name1), false);
        $name2Matches = array_fill(0, strlen($name2), false);
        for ($i = 0; $i < strlen($name1); $i++) {
            $low = max(0, $i - $matchDistance);
            $high = min($i + $matchDistance + 1, strlen($name2));
            for ($j = $low; $j < $high; $j++) {
                if (!$name2Matches[$j] && $name1[$i] == $name2[$j]) {
                    $name1Matches[$i] = $name2Matches[$j] = true;
                    $matches++;
                    break;
                }
            }
        }
        // Finalizar si no se encuentran coincidencias en los caracteres
        if ($matches == 0) {
            return 0.0;
        }

        // Se calcula la cantidad de transposiciones necesarias
        // para convertir una cadena en la otra
        $k = $transpositions = 0;
        for ($i = 0; $i < strlen($name1); $i++) {
            if ($name1Matches[$i]) {
                for ($j = $k; $j < strlen($name2); $j++) {
                    if ($name2Matches[$j]) {
                        $k = $j + 1;
                        break;
                    }
                }
                if ($name1[$i] != $name2[$j]) {
                    $transpositions++;
                }
            }
        }

        // Calcular la similitud Jaro-Winkle, para esto sumar la proporción
        // de caracteres coincidentes en ambas cadenas,
        // la proporción de caracteres coincidentes en la segunda cadena que
        // no se encuentran dentro de una transposición, y un ajuste adicional
        // que se aplica para dar mayor peso a los caracteres iniciales en común.
        $similarity = ((float) $matches / strlen($name1)
                + (float) $matches / strlen($name2)
                + (float) ($matches - $transpositions) / $matches) / 3.0;

        // Se calcula el factor de prefijo común, que mide la cantidad de caracteres
        // coincidentes al comienzo de las dos cadenas. Este factor se utiliza para
        // aumentar la similitud Jaro si las dos cadenas tienen un prefijo común.
        $prefixLen = 0;
        for ($i = 0; $i < min(4, min(strlen($name1), strlen($name2))); $i++) {
            if ($name1[$i] == $name2[$i]) {
                $prefixLen++;
            } else {
                break;
            }
        }

        // Se usa el factor de escala en 0.1, que es una pequeña corrección
        // que se utiliza para ajustar la similitud Jaro en función de la longitud
        // de las cadenas y el factor de prefijo común. El factor de escala se
        // utiliza para ajustar la similitud Jaro para que tenga en cuenta la
        // importancia del prefijo común y la longitud de las cadenas
        $scaleFactor = 0.1;
        $similarity += $prefixLen * $scaleFactor * (1 - $similarity);

        return $similarity;
    }

}
