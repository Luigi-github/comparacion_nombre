<?php

namespace Tests\Unit;

use App\Contracts\MatchSearchService;
use App\Repositories\MatchSearchRepository;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class JaroWinklerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testMacthNames()
    {
        // Instanciar el repositorio
        $matchSearchService = new MatchSearchRepository();

        // Hacer accesible el metodo privado
        $method = new \ReflectionMethod(MatchSearchRepository::class, 'jaroWinkler');
        $method->setAccessible(true);

        // Obtener el resultado de similitud
        $result1 = $method->invokeArgs($matchSearchService, ['Emilse Serrano', 'Emilse Vargas']);
        $result2 = $method->invokeArgs($matchSearchService, ['Oscar Noriega Redondo', 'Oscar Noriega Redondo']);
        $result3 = $method->invokeArgs($matchSearchService, ['Naya Victoria Quiñones Rojas', 'Naya Victoria Quiñones Rojas']);

        // Validar similitudes
        $this->assertEquals(round($result1 * 100, 2), 86.7);
        $this->assertEquals(round($result2 * 100, 2), 100);
        $this->assertEquals(round($result3 * 100, 2), 100);
    }
}
