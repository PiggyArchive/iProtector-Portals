<?php

namespace iProtectorPortals;

use iProtectorPortals\Events\PortalUseEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\Listener;
use pocketmine\level\Location;
use pocketmine\math\Vector3;

class EventListener implements Listener{
    public function __construct($plugin){
	$this->plugin = $plugin;
    }

	public function onPLayerMove(PlayerMoveEvent $event){	
		$player = $event->getPlayer();	
		foreach($this->plugin->portals as $name => $data){
			foreach($this->plugin->getServer()->getPluginManager()->getPlugin("iProtector")->areas as $area){ 
				if($area->getName() == $name){
      				if($area->contains(new Vector3($player->x, $player->y, $player->z), $player->getLevel()->getName())){  
      					$from = new Location($player->x, $player->y, $player->z, $player->yaw, $player->pitch, $player->getLevel());
      					$to = new Location($data[0], $data[1], $data[2], $player->yaw, $player->pitch, $this->plugin->getServer()->getLevelByName($data[3]));
						$ev = new PortalUseEvent($player, $from, $to, $name);
						$this->plugin->getServer()->getPluginManager()->callEvent($ev);
						if($ev->isCancelled()){
							return true;	
						}
      					if(!$this->plugin->getServer()->isLevelLoaded($data[3])){
      						$this->plugin->getServer()->loadLevel($data[3]);
      					}
      					$level = $this->plugin->getServer()->getLevelByName($data[3]);
      					$event->setFrom($ev->getTo()); //compatibility for anti-cheat plugins
      					$event->setTo($ev->getTo());
      				}
      			}   
      		}
		}	
	}

}
