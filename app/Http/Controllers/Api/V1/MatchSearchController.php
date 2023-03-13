<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\MatchSearchService;
use App\Enum\ExecutionStateEnum;
use App\Http\Controllers\Controller;
use App\Models\MatchSearch;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MatchSearchController extends Controller
{

    protected MatchSearchService $matchSearchService;

    public function __construct(MatchSearchService $matchSearchService)
    {
        $this->matchSearchService = $matchSearchService;
    }

    /**
     * Obtener personas por nombre y porcentaje de coincidencia
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getMatchNames(Request $request): Response
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'string|required',
            'match_percentage' => 'regex:/^\d+(\.\d{1,2})?$/|required|min:0|max:100'
        ]);
        if ($validator->fails()) {
            return response([
                'execution_state' => $validator->errors()->first()
            ], Response::HTTP_BAD_REQUEST);
        }

        // Creamos el registro de busqueda
        DB::beginTransaction();
        $matchSearch = MatchSearch::create($data);
        DB::commit();

        DB::beginTransaction();
        try{
            // Obtener las coincidencias
            $matchSearchLog = $this->matchSearchService->getMatchNames($matchSearch->id, $data);

            // Cantidad de coincidencias encontradas
            $coincidencesQty = count($matchSearchLog->coincidences) > 0;

            // Actualizar el estado de la busqueda
            $matchSearchLog->execution_state = $coincidencesQty ?
                ExecutionStateEnum::WITH_MATCH : ExecutionStateEnum::WITHOUT_MATCH;
            $matchSearchLog->save();

            DB::commit();

            return response($matchSearchLog, $coincidencesQty ? Response::HTTP_OK : Response::HTTP_PARTIAL_CONTENT);
        }catch (Exception $e){
            // Cerrar transacción
            DB::rollBack();

            // Actualizar el estado de la busqueda
            DB::beginTransaction();
            $matchSearch->execution_state = ExecutionStateEnum::SYSTEM_ERROR;
            $matchSearch->save();
            DB::commit();

            return response($matchSearch, Response::HTTP_CONFLICT);
        }
    }

    /**
     * Obtener personas por uuid de consulta previa
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $uuid
     * @return \Illuminate\Http\Response
     */
    public function getMatchNamesByUUID(int $uuid): Response
    {
        $validator = Validator::make(['id' => $uuid], [
            'id' => 'integer|required|exists:match_searches'
        ]);
        if ($validator->fails()) {
            return response([
                'execution_state' => $validator->errors()->first()
            ], Response::HTTP_BAD_REQUEST);
        }

        // Obtener log de resultados previos
        $matchSearchLog = $this->matchSearchService->getMatchNamesByUUID($uuid);

        // Cantidad de coincidencias encontradas
        $coincidencesQty = count($matchSearchLog->coincidences) > 0;

        return response($matchSearchLog, $coincidencesQty ? Response::HTTP_OK : Response::HTTP_PARTIAL_CONTENT);
    }
}
