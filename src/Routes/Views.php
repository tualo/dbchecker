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
                select table_name,"" as checktype, "" as msg from 
                information_schema.TABLES v
                where TABLE_SCHEMA=DATABASE()
                AND TABLE_TYPE="VIEW"
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

        BasicRoute::add('/dbchecker/views/recreate', function () {
            $db = App::get('session')->getDB();
            App::result('success', false);

            try {
                if (!isset($_POST['view']) || empty($_POST['view'])) {
                    throw new \Exception('View parameter is required');
                }
                $sql = 'SELECT
                    TABLE_NAME,
                    VIEW_DEFINITION
                FROM information_schema.VIEWS
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = "' . $_POST['view'] . '" 
                ';
                $viewDefinitionRow = $db->singleRow($sql);
                if (empty($viewDefinitionRow)) {
                    throw new \Exception('View not found');
                }
                $viewDefinition = 'CREATE OR REPLACE VIEW `' . $_POST['view'] . '` AS ' . $viewDefinitionRow['view_definition'];
                App::result('sql', $viewDefinition);
                $db->direct($viewDefinition);

                // check if the view was successfully recreated
                try {
                    $db->direct('SHOW FIELDS FROM `' . $_POST['view'] . '`');
                } catch (\Exception $e) {
                    // if the first attempt fails, try again
                    try {
                        $db->direct($viewDefinitionRow['view_definition']);
                    } catch (\Exception $e) {
                        throw new \Exception('' . $e->getMessage());
                    }
                }


                App::result('success', true);
            } catch (\Exception $e) {
                App::result('msg', $e->getMessage());
            }
            App::contenttype('application/json');
        }, ['post'], true, [], self::scope());
    }
}
