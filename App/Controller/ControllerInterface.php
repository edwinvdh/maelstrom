<?php

namespace App\Controller;

use App\Helper\Request;

interface ControllerInterface
{
    public function get(Request $request): ControllerInterface;

    public function post(Request $request): ControllerInterface;

    public function delete(Request $request): ControllerInterface;
}
