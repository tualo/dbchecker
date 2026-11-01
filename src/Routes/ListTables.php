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
                $sql = '
                select distinct table_name,VIEW_DEFINITION, definer from
                (
                SELECT
                    
                    c.TABLE_NAME,
                     v.TABLE_TYPE,
                    c.COLUMN_NAME,
                    c.COLUMN_TYPE,
                    c.CHARACTER_SET_NAME,
                    c.COLLATION_NAME,
                    definer,
                    VIEW_DEFINITION
                FROM information_schema.COLUMNS c
                    join information_schema.TABLES v 
                        on v.TABLE_SCHEMA = c.TABLE_SCHEMA AND v.TABLE_NAME = c.TABLE_NAME
                    join information_schema.VIEWS v2
                        on v2.TABLE_SCHEMA = c.TABLE_SCHEMA AND v2.TABLE_NAME = c.TABLE_NAME
                    WHERE c.TABLE_SCHEMA = DATABASE()  
                    AND c.CHARACTER_SET_NAME <> "utf8mb4"
                    and c.COLLATION_NAME <> "utf8mb4_general_ci"
                ORDER BY c.TABLE_NAME, c.ORDINAL_POSITION
                ) as subquery
                ';

                $list = $db->direct($sql);
                for ($i = 0; $i < count($list); $i++) {
                    $sql = 'create or replace view `' . $list[$i]['table_name'] . '` as ' . $list[$i]['view_definition'];
                    $db->direct($sql);
                }
                App::result('success', true);
                App::result('tables', $list);
            } catch (\Exception $e) {
                App::result('msg', $e->getMessage());
            }
            App::contenttype('application/json');
        }, ['get'], true, [], self::scope());

        BasicRoute::add('/dbchecker/list-views', function () {
            $db = App::get('session')->getDB();
            try {
                $sql = 'SELECT
                    TABLE_NAME,
                    VIEW_DEFINITION
                FROM information_schema.VIEWS
                WHERE TABLE_SCHEMA = DATABASE()
                ORDER BY TABLE_NAME';

                App::result('success', true);
                App::result('files', $db->direct($sql));
            } catch (\Exception $e) {
                App::result('msg', $e->getMessage());
            }
            App::contenttype('application/json');
        }, ['get'], true, [], self::scope());
    }
}
