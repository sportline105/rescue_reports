<?php

namespace In2code\Firefighter\Utility;

class EventVehicleAssignmentUtility
{
    /**
     * itemsProcFunc for field "Fahrzeugeinsatz"
     *
     * @param array $config
     */
    public function getAssignmentOptions(array &$config)
    {
        // Beispiel: statischer Eintrag zum Testen
        $config['items'][] = ['Testfahrzeug A', 1];
        $config['items'][] = ['Testfahrzeug B', 2];

        // Hinweis: Hier sollte ggf. dynamisch basierend auf dem Event die Stationen/Fahrzeuge gesammelt werden
    }
}