<?php
namespace App\PFE;

class PFERouter
{
    public static function routes($app, PFEController $controller)
    {
        $app->post('/pfe', function($request, $response) use ($controller) {
            $data = $request->getParsedBody();
            $registro = $controller->criar($data);
            return $response->withJson($registro);
        });

        $app->get('/pfe', function($request, $response) use ($controller) {
            $registros = $controller->listar();
            return $response->withJson($registros);
        });

        $app->get('/pfe/{id}', function($request, $response, $args) use ($controller) {
            $registro = $controller->obter((int)$args['id']);
            return $response->withJson($registro);
        });

        $app->put('/pfe/{id}', function($request, $response, $args) use ($controller) {
            $data = $request->getParsedBody();
            $data['id'] = (int)$args['id'];
            $registro = $controller->atualizar($data);
            return $response->withJson($registro);
        });

        $app->delete('/pfe/{id}', function($request, $response, $args) use ($controller) {
            $controller->remover((int)$args['id']);
            return $response->withStatus(204);
        });
    }
} 