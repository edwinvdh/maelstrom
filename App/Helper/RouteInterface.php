<?php

namespace App\Helper;

interface RouteInterface
{
    public function get(Request $request);

    public function post(Request $request);

    public function put(Request $putRequest);

    public function patch(Request $patchRequest);

    public function delete(Request $deleteRequest);
}
