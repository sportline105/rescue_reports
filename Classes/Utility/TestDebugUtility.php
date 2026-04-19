<?php
declare(strict_types=1);

namespace nkfire\RescueReports\Utility;

class TestDebugUtility
{
    public function debugItems(array &$config): void
    {
        $config['items'][] = ['Debug funktioniert 🎉', 1234];
    }
}
