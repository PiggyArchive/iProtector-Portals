<?php

namespace iProtectorPortals\Commands;

use iProtectorPortals\Events\PortalCreateEvent;
use iProtectorPortals\Events\PortalRemoveEvent;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class PortalCommand extends VanillaCommand{
    public function __construct($name, $plugin){
        parent::__construct(
            $name, "Setup a portal", "/portal <name> <x> <y> <z> [world]"
        );
        $this->setPermission("portal.command.portal");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, $currentAlias, array $args){
    	if(!$this->testPermission($sender)){
            return true;
    	}
        if(!isset($args[0])){
            $sender->sendMessage("/portal <create|remove|list>");
            return false;
        }
        switch($args[0]){
            case "create":
                if(count($args) < 5){
                    $sender->sendMessage("/portal create <name> <x> <y> <z> [level]");
                    return false;
                }
                if(!(isset($this->plugin->getServer()->getPluginManager()->getPlugin("iProtector")->areas[strtolower($args[1])]))){
                    $sender->sendMessage("§cArea doesn't exist.");
                    return false;
                }
                if(!is_numeric($args[2]) || !is_numeric($args[3]) || !is_numeric($args[4])){
                    $sender->sendMessage("§cInvalid position.");
                    return false;            
                }
                $level = null;
                if(isset($args[5])){
                    if(!$this->plugin->getServer()->isLevelGenerated($args[5])){
                        $sender->sendMessage("§cInvalid world. (case-sensitive)");
                        return false;        
                    }
                    $level = $args[5];       
                }else{
                    if($sender instanceof Player){
                        $level = $sender->getLevel()->getName();
                        $sender->sendMessage("§cNo world provided, using current world.");
                    }else{
                        $level = $this->plugin->getServer()->getDefaultLevel()->getName();
                        $sender->sendMessage("§cNo world provided, using default world.");                        
                    }
                }
                $ev = new PortalCreateEvent($args[1], $args[2], $args[3], $args[4], $level);
                $this->plugin->getServer()->getPluginManager()->callEvent($ev);
                if($ev->isCancelled()){
                    return true;    
                }
                $this->plugin->createPortal($ev->getPortal(), $ev->getX(), $ev->getY(), $ev->getZ(), $ev->getLevel());
                $sender->sendMessage("§aPortal created.");
                return true;
            case "remove":
                if(!isset($args[1])){
                    $sender->sendMessage("/portal remove <name>");
                    return false;
                }
                if($this->plugin->portalExists(strtolower($args[1]))){
                    $ev = new PortalRemoveEvent($args[1]);
                    $this->plugin->getServer()->getPluginManager()->callEvent($ev);
                    if($ev->isCancelled()){
                        return true;    
                    }
                    $this->plugin->removePortal(strtolower($args[1]));
                    $sender->sendMessage("§aPortal removed.");
                }else{
                    $sender->sendMessage("§cPortal doesn't exist.");
                    return false;
                }
                return true;
            case "list":
                $portals = "";
                $first = false;
                foreach($this->plugin->portals as $name => $data){
                    if(!$first){
                        $portals = $name;
                        $first = true;
                    }else{
                        $portals = $portals . ", " . $name;
                    }
                }

                $sender->sendMessage("§aPortals:§f " . $portals);
                return true;
        }
    }

}
