<?php

namespace In2code\Firefighter\Utility;

class TestDebugUtility
{
    public function debugItems(array &$config): void
    {
        $config['items'][] = ['Debug funktioniert 🎉', 1234];
    }
}
