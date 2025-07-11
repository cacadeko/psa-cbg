<?php
namespace App\QualidadeSono;

class QualidadeSonoRouter
{
    public static function routes($app, QualidadeSonoController $controller)
    {
        $app->post('/qualidade-sono', function($request, $response) use ($controller) {
            $data = $request->getParsedBody();
            $registro = $controller->criar($data);
            return $response->withJson($registro);
        });

        $app->get('/qualidade-sono', function($request, $response) use ($controller) {
            $registros = $controller->listar();
            return $response->withJson($registros);
        });

        $app->get('/qualidade-sono/{id}', function($request, $response, $args) use ($controller) {
            $registro = $controller->obter((int)$args['id']);
            return $response->withJson($registro);
        });

        $app->put('/qualidade-sono/{id}', function($request, $response, $args) use ($controller) {
            $data = $request->getParsedBody();
            $data['id'] = (int)$args['id'];
            $registro = $controller->atualizar($data);
            return $response->withJson($registro);
        });

        $app->delete('/qualidade-sono/{id}', function($request, $response, $args) use ($controller) {
            $controller->remover((int)$args['id']);
            return $response->withStatus(204);
        });
    }
} 