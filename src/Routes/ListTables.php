<?php

namespace Tualo\Office\DBChecker\Routes;

use Tualo\Office\DBChecker\Scopes\DBCheckerScope;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\Basic\RouteSecurityHelper;


class ListTables extends \Tualo\Office\Basic\RouteWrapper
{
    public static function scope(): string
    {
        return DBCheckerScope::identifier();
    }
    public static function register()
    {
        BasicRoute::add('/dbchecker/list-tables', function () {
            $db = App::get('session')->getDB();
            try {
                $sql = 'SELECT
                    TABLE_NAME,
                    COLUMN_NAME,
                    COLUMN_TYPE,
                    CHARACTER_SET_NAME,
                    COLLATION_NAME
                FROM information_schema.COLUMNS
                    WHERE TABLE_SCHEMA = DATABASE()  AND CHARACTER_SET_NAME IS NOT NULL
                ORDER BY TABLE_NAME, ORDINAL_POSITION';

                App::result('success', true);
                App::result('files', $db->direct($sql));
            } catch (\Exception $e) {
                App::result('msg', $e->getMessage());
            }
            App::contenttype('application/json');
        }, ['get'], true, [], self::scope());
    }
}
