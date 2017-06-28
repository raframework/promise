<?php

/**
 * User: coderd
 * Date: 2017/6/28
 * Time: 15:02
 */

namespace tests\mock\App\Resource;


use Ra\Http\Request;
use Ra\Http\Response;

class Json
{
    public function lis(Request $request, Response $response)
    {
        $data = [
            'array' => [
                'int' => 1,
            ]
        ];

        $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($data));
    }
}