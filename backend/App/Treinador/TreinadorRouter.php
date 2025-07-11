<?php
namespace App\Treinador;

class TreinadorRouter
{
    public static function routes($app, TreinadorController $controller)
    {
        $app->post('/treinadores', function($request, $response) use ($controller) {
            $data = $request->getParsedBody();
            $treinador = $controller->criar($data);
            return $response->withJson($treinador);
        });

        $app->get('/treinadores', function($request, $response) use ($controller) {
            $treinadores = $controller->listar();
            return $response->withJson($treinadores);
        });

        $app->get('/treinadores/{id}', function($request, $response, $args) use ($controller) {
            $treinador = $controller->obter((int)$args['id']);
            return $response->withJson($treinador);
        });

        $app->put('/treinadores/{id}', function($request, $response, $args) use ($controller) {
            $data = $request->getParsedBody();
            $data['id'] = (int)$args['id'];
            $treinador = $controller->atualizar($data);
            return $response->withJson($treinador);
        });

        $app->delete('/treinadores/{id}', function($request, $response, $args) use ($controller) {
            $controller->remover((int)$args['id']);
            return $response->withStatus(204);
        });
    }
} 