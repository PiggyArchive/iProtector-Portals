<?php
namespace iProtectorPortals\Events;

use pocketmine\event\Cancellable;
use pocketmine\event\Event;
use pocketmine\Player;

class PortalRemoveEvent extends Event implements Cancellable {
    public static $handlerList = null;

    private $portal;

    public function __construct($portal) {
        $this->portal = $portal;
    }

    public function getPortal() {
        return $this->portal;
    }

}
