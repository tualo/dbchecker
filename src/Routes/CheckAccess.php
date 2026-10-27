<?php

namespace Tualo\Office\DBChecker\Routes;

use Tualo\Office\DBChecker\Scopes\DBCheckerScope;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\Basic\RouteSecurityHelper;

class CheckAccess extends \Tualo\Office\Basic\RouteWrapper
{
    public static function scope(): string
    {
        return DBCheckerScope::identifier();
    }
    public static function register()
    {
        BasicRoute::add('/js/dbchecker', function ($matches) {
            App::result('success', true);
        }, ['get'], true, [], self::scope());
    }
}
