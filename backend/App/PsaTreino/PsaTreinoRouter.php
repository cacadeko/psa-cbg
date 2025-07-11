<?php
namespace App\PsaTreino;

class PsaTreinoRouter
{
    public static function routes($app, PsaTreinoController $controller)
    {
        $app->post('/psa-treino', function($request, $response) use ($controller) {
            $data = $request->getParsedBody();
            $registro = $controller->criar($data);
            return $response->withJson($registro);
        });

        $app->get('/psa-treino', function($request, $response) use ($controller) {
            $registros = $controller->listar();
            return $response->withJson($registros);
        });

        $app->get('/psa-treino/{id}', function($request, $response, $args) use ($controller) {
            $registro = $controller->obter((int)$args['id']);
            return $response->withJson($registro);
        });

        $app->put('/psa-treino/{id}', function($request, $response, $args) use ($controller) {
            $data = $request->getParsedBody();
            $data['id'] = (int)$args['id'];
            $registro = $controller->atualizar($data);
            return $response->withJson($registro);
        });

        $app->delete('/psa-treino/{id}', function($request, $response, $args) use ($controller) {
            $controller->remover((int)$args['id']);
            return $response->withStatus(204);
        });
    }
} 