<?php
namespace In2code\Firefighter\Form\Element;

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use In2code\Firefighter\Utility\EventVehicleAssignmentUtility;

class EventVehicleAssignmentElement extends AbstractFormElement
{
    public function render()
    {
        $resultArray = $this->initializeResultArray();

        // Hole die aktuelle Event-UID
        $eventUid = (int)($this->data['databaseRow']['uid'] ?? 0);

        // Fahrzeuge holen (du kannst hier deine Utility-Klasse verwenden)
        $utility = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\In2code\Firefighter\Utility\EventVehicleAssignmentUtility::class);
        $vehicles = [];
        if ($eventUid) {
            $stationUids = $utility->getRelatedStationUids($eventUid);
            $vehicles = $utility->getVehiclesWithStationName($stationUids);
        }

        // Selectbox bauen
        $html = '<select name="' . htmlspecialchars($this->data['parameterArray']['itemFormElName']) . '">';
        foreach ($vehicles as $vehicle) {
            $label = $vehicle['station_name'] . ' – ' . $vehicle['car_name'];
            $value = $vehicle['car_uid'];
            $html .= '<option value="' . htmlspecialchars($value) . '">' . htmlspecialchars($label) . '</option>';
        }
        $html .= '</select>';

        $resultArray['html'] = $html;
        return $resultArray;
    }
}
