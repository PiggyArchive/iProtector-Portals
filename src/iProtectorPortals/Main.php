<?php

namespace iProtectorPortals;

use iProtectorPortals\Commands\PortalCommand;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase{

	public $portals = array();
	public $portallist;

	public function onEnable(){
		@mkdir($this->getDataFolder());
    	$this->portallist = new Config($this->getDataFolder() . "portals.yml", Config::YAML);
    	$this->loadPortals();
    	$this->getServer()->getCommandMap()->register('portal', new PortalCommand('portal', $this));
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		$this->getLogger()->info("Enabled!");
	}

	public function loadPortals(){
		foreach($this->portallist->getAll() as $name => $data){
			if(!(isset($this->getServer()->getPluginManager()->getPlugin("iProtector")->areas[strtolower($name)]))){ //Removes portal if area is removed
				$this->portallist->remove(strtolower($name));
				continue;
			}
			if(!$this->getServer()->isLevelGenerated($data[3])){ //Removes portal if world is removed
				$this->portallist->remove(strtolower($name));
				continue;
			}
			$this->portals[strtolower($name)] = $data;
		}
		$this->portallist->save();
	}

	public function savePortals(){
		foreach($this->portals as $name => $data){
			if(!(isset($this->getServer()->getPluginManager()->getPlugin("iProtector")->areas[strtolower($name)]))){ //Removes portal if area is removed
				$this->portallist->remove(strtolower($name));
				continue;
			}
			if(!$this->getServer()->isLevelGenerated($data[3])){ //Removes portal if world is removed
				$this->portallist->remove(strtolower($name));
				continue;
			}
			$this->portallist->set($name, $data);
		}
		$this->portallist->save();
	}

	public function createPortal($name, $x, $y, $z, $level){
		$this->portals[strtolower($name)] = array($x, $y, $z, $level);
		$this->savePortals();
	}

	public function removePortal($name){
		unset($this->portals[strtolower($name)]);
		$this->portallist->remove(strtolower($name));
		$this->savePortals();
	}

	public function portalExists($name){
		return isset($this->portals[strtolower($name)]);
	}

}
