<?php
namespace iProtectorPortals\Events;

use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerEvent;
use pocketmine\level\Location;
use pocketmine\Player;

class PortalUseEvent extends PlayerEvent implements Cancellable {
    public static $handlerList = null;

    private $from;
    private $to;

    public function __construct(Player $player, Location $from, Location $to, $portal) {
        $this->player = $player;
        $this->from = $from;
        $this->to = $to;
        $this->portal = $portal;
    }

    public function getFrom() {
        return $this->from;
    }

    public function setFrom(Location $from) {
        $this->from = $from;
    }

    public function getTo() {
        return $this->to;
    }

    public function setTo(Location $to) {
        $this->to = $to;
    }

    public function getPortal() {
        return $this->portal;
    }

    public function setPortal($portal) {
        $this->portal = $portal;
    }

}
