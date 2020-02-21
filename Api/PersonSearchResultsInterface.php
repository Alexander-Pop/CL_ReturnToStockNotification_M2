<?php

/* Glory to Ukraine! Glory to the heros! */

namespace Codelegacy\ReturnToStockNotification\Api;

interface PersonSearchResultsInterface
{
    /**
     * @return array|null
     */
    public function getItems();

    /**
     * @param array|null $items
     * @return PersonSearchResultsInterface
     */
    public function setItems(array $items=null);
}