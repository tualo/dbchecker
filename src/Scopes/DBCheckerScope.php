<?php

namespace Tualo\Office\DBChecker\Scopes;

use Tualo\Office\Basic\Scope;

class DBCheckerScope extends Scope
{
    protected static string $identifier = 'dbchecker';
    protected static array $name = [
        'EN' => 'DBChecker',
        'DE' => 'DBChecker',
    ];
    protected static array $description = [
        'EN' => 'Access and manage database schema.',
        'DE' => 'Auf das Datenbankschema zugreifen und verwalten.',
    ];
}
