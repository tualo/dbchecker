<?php

namespace Tualo\Office\DBChecker\Middlewares;

use Tualo\Office\Basic\TualoApplication;
use Tualo\Office\Basic\IMiddleware;

class Middleware implements IMiddleware
{
    public static function register()
    {
        TualoApplication::use('dbchecker', function () {
            /*
            try {
                TualoApplication::javascript('dbchecker-lib', "./js/dbchecker/lib/dbchecker.js", []);
                TualoApplication::module('dbchecker', "./js/dbchecker/lib/app.js", []);
                TualoApplication::stylesheet("./js/dbchecker/lib/css/dbchecker.css");
            } catch (\Exception $e) {
                TualoApplication::set('maintanceMode', 'on');
                TualoApplication::addError($e->getMessage());
            }
            */
        }, -100, [
            'needActiveSession' => true,
        ]); // should be one of the last
    }
}
