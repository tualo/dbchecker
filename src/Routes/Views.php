<?php

namespace Tualo\Office\DBChecker\Routes;

use Tualo\Office\DBChecker\Scopes\DBCheckerScope;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\Basic\RouteSecurityHelper;


class Views extends \Tualo\Office\Basic\RouteWrapper
{
    public static function scope(): string
    {
        return DBCheckerScope::identifier();
    }
    public static function register()
    {
        BasicRoute::add('/dbchecker/views', function () {
            $db = App::get('session')->getDB();
            try {
                $sql = '
                select table_name,"" as checktype from 
                information_schema.TABLES v
                where TABLE_SCHEMA=DATABASE()
                ';
                $list = $db->direct($sql);
                App::result('success', true);
                App::result('data', $list);
            } catch (\Exception $e) {
                App::result('msg', $e->getMessage());
            }
            App::contenttype('application/json');
        }, ['get'], true, [], self::scope());

        BasicRoute::add('/dbchecker/views/check', function () {
            $db = App::get('session')->getDB();
            App::result('success', false);

            try {
                if (!isset($_POST['view']) || empty($_POST['view'])) {
                    throw new \Exception('View parameter is required');
                }

                /*
                $sql = '
                select distinct table_name, definer from
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
                */

                $viewName = $_POST['view'];
                // sanitize view name to prevent SQL injection
                $viewName = preg_replace('/[^a-zA-Z0-9_\s]/', '', $viewName);
                $db->direct('show create view `' . $viewName . '`');

                $db->direct('SHOW FIELDS FROM `' . $viewName . '`');
                App::result('success', true);
            } catch (\Exception $e) {
                App::result('msg', $e->getMessage());
            }
            App::contenttype('application/json');
        }, ['post'], true, [], self::scope());
    }
}
