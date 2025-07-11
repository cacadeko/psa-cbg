<?php
namespace App\Jogo;

class JogoRouter
{
    public static function routes($app, JogoController $controller)
    {
        $app->post('/jogos', function($request, $response) use ($controller) {
            $data = $request->getParsedBody();
            $jogo = $controller->criar($data);
            return $response->withJson($jogo);
        });

        $app->get('/jogos', function($request, $response) use ($controller) {
            $jogos = $controller->listar();
            return $response->withJson($jogos);
        });

        $app->get('/jogos/{id}', function($request, $response, $args) use ($controller) {
            $jogo = $controller->obter((int)$args['id']);
            return $response->withJson($jogo);
        });

        $app->put('/jogos/{id}', function($request, $response, $args) use ($controller) {
            $data = $request->getParsedBody();
            $data['id'] = (int)$args['id'];
            $jogo = $controller->atualizar($data);
            return $response->withJson($jogo);
        });

        $app->delete('/jogos/{id}', function($request, $response, $args) use ($controller) {
            $controller->remover((int)$args['id']);
            return $response->withStatus(204);
        });
    }
} 