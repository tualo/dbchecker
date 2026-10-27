<?php

namespace Tualo\Office\DBChecker\Routes;

use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\DBChecker\Scopes\DBCheckerScope;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\Basic\RouteSecurityHelper;

class Loader extends \Tualo\Office\Basic\RouteWrapper
{
    public static function scope(): string
    {
        return DBCheckerScope::identifier();
    }
    public static function register()
    {

        BasicRoute::add('/js/dbchecker/(?P<lib>[\w.\-]+)/(?P<file>[\w.\/\-]+)', function ($matches) {
            $path = dirname(__DIR__, 1) . '/js/lazy';

            if ($matches['lib'] == 'lib') {
                $path = dirname(__DIR__, 1) . '/js/lib';
            }
            if (file_exists($path . '/' . $matches['file'])) {
                RouteSecurityHelper::serveSecureStaticFile(
                    $matches['file'],
                    $path . '/',
                    ['js', 'css'],
                    ['js' => 'application/javascript', 'css' => 'text/css']
                );
            }
        }, ['get'], true);
    }
}
