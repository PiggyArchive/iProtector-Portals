<?php
namespace iProtectorPortals\Events;

use pocketmine\event\Cancellable;
use pocketmine\event\Event;
use pocketmine\Player;

class PortalCreateEvent extends Event implements Cancellable {
    public static $handlerList = null;

    private $portal;
    private $x;
    private $y;
    private $z;
    private $level;

    public function __construct($portal, $x, $y, $z, $level) {
        $this->portal = $portal;
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
        $this->level = $level;
    }

    public function getPortal() {
        return $this->portal;
    }

    public function getX() {
        return $this->x;
    }

    public function setX($x) {
        $this->x = $x;
    }

    public function getY() {
        return $this->y;
    }

    public function setY($y) {
        $this->y = $y;
    }

    public function getZ() {
        return $this->z;
    }

    public function setZ($z) {
        $this->z = $z;
    }

    public function getLevel() {
        return $this->level;
    }

    public function setLevel($level) {
        $this->level = $level;
    }

}
