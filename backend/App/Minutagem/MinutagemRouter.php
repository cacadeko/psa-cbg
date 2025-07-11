<?php
namespace App\Minutagem;

class MinutagemRouter
{
    public static function routes($app, MinutagemController $controller)
    {
        $app->post('/minutagem', function($request, $response) use ($controller) {
            $data = $request->getParsedBody();
            $registro = $controller->criar($data);
            return $response->withJson($registro);
        });

        $app->get('/minutagem', function($request, $response) use ($controller) {
            $registros = $controller->listar();
            return $response->withJson($registros);
        });

        $app->get('/minutagem/{id}', function($request, $response, $args) use ($controller) {
            $registro = $controller->obter((int)$args['id']);
            return $response->withJson($registro);
        });

        $app->put('/minutagem/{id}', function($request, $response, $args) use ($controller) {
            $data = $request->getParsedBody();
            $data['id'] = (int)$args['id'];
            $registro = $controller->atualizar($data);
            return $response->withJson($registro);
        });

        $app->delete('/minutagem/{id}', function($request, $response, $args) use ($controller) {
            $controller->remover((int)$args['id']);
            return $response->withStatus(204);
        });
    }
} 