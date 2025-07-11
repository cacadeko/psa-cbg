<?php
namespace App\Registro;

class RegistroRouter
{
    public static function routes($app, RegistroController $controller)
    {
        $app->post('/registros', function($request, $response) use ($controller) {
            $data = $request->getParsedBody();
            $registro = $controller->criar($data);
            return $response->withJson($registro);
        });

        $app->get('/registros', function($request, $response) use ($controller) {
            $registros = $controller->listar();
            return $response->withJson($registros);
        });

        $app->get('/registros/{id}', function($request, $response, $args) use ($controller) {
            $registro = $controller->obter((int)$args['id']);
            return $response->withJson($registro);
        });

        $app->put('/registros/{id}', function($request, $response, $args) use ($controller) {
            $data = $request->getParsedBody();
            $data['id'] = (int)$args['id'];
            $registro = $controller->atualizar($data);
            return $response->withJson($registro);
        });

        $app->delete('/registros/{id}', function($request, $response, $args) use ($controller) {
            $controller->remover((int)$args['id']);
            return $response->withStatus(204);
        });
    }
} 