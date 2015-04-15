<?php

/*
 * CustomAlerts (v1.2) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: http://www.evolsoft.tk
 * Date: 15/04/2015 12:00 AM (UTC)
 * Copyright & License: (C) 2014-2015 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/CustomAlerts/blob/master/LICENSE)
 */

namespace CustomAlerts;

use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;

class EventListener implements Listener {
	
	public function __construct(CustomAlertsAPI $plugin){
        $this->plugin = $plugin;
    }
    
    public function onPlayerJoin(PlayerJoinEvent $event){
    	$player = $event->getPlayer();
    	$status = 0;
    	CustomAlertsAPI::getAPI()->setJoinMessage($event->getJoinMessage());
    	//Get First Join
    	if(CustomAlertsAPI::getAPI()->hasJoinedFirstTime($player)){
    		//Register FirstJoin
    		CustomAlertsAPI::getAPI()->registerFirstJoin($player);
    		//Check if FirstJoin message is enabled
    		if(CustomAlertsAPI::getAPI()->isDefaultFirstJoinMessageEnabled()){
    			CustomAlertsAPI::getAPI()->setJoinMessage(CustomAlertsAPI::getAPI()->getDefaultFirstJoinMessage($player));
    			$status = 1;
    		}
    	}
    	//Default Join Message
    	if($status == 0){
    		//Check if Join message is hidden
    		if(CustomAlertsAPI::getAPI()->isDefaultJoinMessageHidden()){
    			CustomAlertsAPI::getAPI()->setJoinMessage("");
    		}else{
    			//Check if Join message is custom
    			if(CustomAlertsAPI::getAPI()->isDefaultJoinMessageCustom()){
    				CustomAlertsAPI::getAPI()->setJoinMessage(CustomAlertsAPI::getAPI()->getDefaultJoinMessage($player));
    			}
    		}
    	}
    	//Extensions Events
    	//HIGHEST
    	foreach(CustomAlertsAPI::getAPI()->getAllExtensions(CustomAlertsAPI::PRIORITY_HIGHEST) as $extension){
    		if($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()) && method_exists($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()), "onCustomAlertsJoinEvent")){
    			$this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName())->onCustomAlertsJoinEvent(new Events\CustomAlertsJoinEvent($player, $event->getJoinMessage()));
    		}
    	}
    	//HIGH
    	foreach(CustomAlertsAPI::getAPI()->getAllExtensions(CustomAlertsAPI::PRIORITY_HIGH) as $extension){
    		if($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()) && method_exists($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()), "onCustomAlertsJoinEvent")){
    			$this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName())->onCustomAlertsJoinEvent(new Events\CustomAlertsJoinEvent($player, $event->getJoinMessage()));
    		}
    	}
    	//NORMAL
    	foreach(CustomAlertsAPI::getAPI()->getAllExtensions(CustomAlertsAPI::PRIORITY_NORMAL) as $extension){
    		if($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()) && method_exists($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()), "onCustomAlertsJoinEvent")){
    			$this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName())->onCustomAlertsJoinEvent(new Events\CustomAlertsJoinEvent($player, $event->getJoinMessage()));
    		}
    	}
    	//LOW
    	foreach(CustomAlertsAPI::getAPI()->getAllExtensions(CustomAlertsAPI::PRIORITY_LOW) as $extension){
    		if($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()) && method_exists($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()), "onCustomAlertsJoinEvent")){
    			$this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName())->onCustomAlertsJoinEvent(new Events\CustomAlertsJoinEvent($player, $event->getJoinMessage()));
    		}
    	}
    	//LOWEST
    	foreach(CustomAlertsAPI::getAPI()->getAllExtensions(CustomAlertsAPI::PRIORITY_LOWEST) as $extension){
    		if($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()) && method_exists($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()), "onCustomAlertsJoinEvent")){
    			$this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName())->onCustomAlertsJoinEvent(new Events\CustomAlertsJoinEvent($player, $event->getJoinMessage()));
    		}
    	}
    	$event->setJoinMessage(CustomAlertsAPI::getAPI()->getJoinMessage());
    }
    
    public function onPlayerQuit(PlayerQuitEvent $event){
    	 $player = $event->getPlayer();
    	 CustomAlertsAPI::getAPI()->setQuitMessage($event->getQuitMessage());
    	 //Check if Quit message is hidden
    	 if(CustomAlertsAPI::getAPI()->isQuitHidden()){
    	 	CustomAlertsAPI::getAPI()->setQuitMessage("");
    	 }else{
    	 	//Check if Quit message is custom
    	 	if(CustomAlertsAPI::getAPI()->isQuitCustom()){
    	 		CustomAlertsAPI::getAPI()->setQuitMessage(CustomAlertsAPI::getAPI()->getDefaultQuitMessage($player));
    	 	}
    	 }
    	 //Extensions Events
    	 //HIGHEST
    	 foreach(CustomAlertsAPI::getAPI()->getAllExtensions(CustomAlertsAPI::PRIORITY_HIGHEST) as $extension){
    	 	if($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()) && method_exists($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()), "onCustomAlertsQuitEvent")){
    	 		$this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName())->onCustomAlertsQuitEvent(new Events\CustomAlertsQuitEvent($player, $event->getQuitMessage()));
    	 	}
    	 }
    	 //HIGH
    	 foreach(CustomAlertsAPI::getAPI()->getAllExtensions(CustomAlertsAPI::PRIORITY_HIGH) as $extension){
    	 	if($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()) && method_exists($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()), "onCustomAlertsQuitEvent")){
    	 		$this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName())->onCustomAlertsQuitEvent(new Events\CustomAlertsQuitEvent($player, $event->getQuitMessage()));
    	 	}
    	 }
    	 //NORMAL
    	 foreach(CustomAlertsAPI::getAPI()->getAllExtensions(CustomAlertsAPI::PRIORITY_NORMAL) as $extension){
    	 	if($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()) && method_exists($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()), "onCustomAlertsQuitEvent")){
    	 		$this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName())->onCustomAlertsQuitEvent(new Events\CustomAlertsQuitEvent($player, $event->getQuitMessage()));
    	 	}
    	 }
    	 //LOW
    	 foreach(CustomAlertsAPI::getAPI()->getAllExtensions(CustomAlertsAPI::PRIORITY_LOW) as $extension){
    	 	if($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()) && method_exists($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()), "onCustomAlertsQuitEvent")){
    	 		$this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName())->onCustomAlertsQuitEvent(new Events\CustomAlertsQuitEvent($player, $event->getQuitMessage()));
    	 	}
    	 }
    	 //LOWEST
    	 foreach(CustomAlertsAPI::getAPI()->getAllExtensions(CustomAlertsAPI::PRIORITY_LOWEST) as $extension){
    	 	if($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()) && method_exists($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()), "onCustomAlertsQuitEvent")){
    	 		$this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName())->onCustomAlertsQuitEvent(new Events\CustomAlertsQuitEvent($player, $event->getQuitMessage()));
    	 	}
    	 }
    	 $event->setQuitMessage(CustomAlertsAPI::getAPI()->getQuitMessage());
    }
    
    public function onWorldChange(EntityLevelChangeEvent $event){
    	$entity = $event->getEntity();
    	CustomAlertsAPI::getAPI()->setWorldChangeMessage("");
    	//Check if the Entity is a Player
    	if($entity instanceof Player){
    		$origin = $event->getOrigin();
    		$target = $event->getTarget();
    		//Check if Default WorldChange Message is enabled
    		if(CustomAlertsAPI::getAPI()->isDefaultWorldChangeMessageEnabled()){
    			CustomAlertsAPI::getAPI()->setWorldChangeMessage(CustomAlertsAPI::getAPI()->getDefaultWorldChangeMessage($player, $origin, $target));
    		}
    		//Extensions Events
    		//HIGHEST
    		foreach(CustomAlertsAPI::getAPI()->getAllExtensions(CustomAlertsAPI::PRIORITY_HIGHEST) as $extension){
    			if($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()) && method_exists($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()), "onCustomAlertsWorldChangeEvent")){
    				$this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName())->onCustomAlertsWorldChangeEvent(new Events\CustomAlertsWorldChangeEvent($player, $origin, $target));
    			}
    		}
    		//HIGH
    		foreach(CustomAlertsAPI::getAPI()->getAllExtensions(CustomAlertsAPI::PRIORITY_HIGH) as $extension){
    			if($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()) && method_exists($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()), "onCustomAlertsWorldChangeEvent")){
    				$this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName())->onCustomAlertsWorldChangeEvent(new Events\CustomAlertsWorldChangeEvent($player, $origin, $target));
    			}
    		}
    		//NORMAL
    		foreach(CustomAlertsAPI::getAPI()->getAllExtensions(CustomAlertsAPI::PRIORITY_NORMAL) as $extension){
    			if($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()) && method_exists($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()), "onCustomAlertsWorldChangeEvent")){
    				$this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName())->onCustomAlertsWorldChangeEvent(new Events\CustomAlertsWorldChangeEvent($player, $origin, $target));
    			}
    		}
    		//LOW
    		foreach(CustomAlertsAPI::getAPI()->getAllExtensions(CustomAlertsAPI::PRIORITY_LOW) as $extension){
    			if($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()) && method_exists($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()), "onCustomAlertsWorldChangeEvent")){
    				$this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName())->onCustomAlertsWorldChangeEvent(new Events\CustomAlertsWorldChangeEvent($player, $origin, $target));
    			}
    		}
    		//LOWEST
    		foreach(CustomAlertsAPI::getAPI()->getAllExtensions(CustomAlertsAPI::PRIORITY_LOWEST) as $extension){
    			if($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()) && method_exists($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()), "onCustomAlertsWorldChangeEvent")){
    				$this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName())->onCustomAlertsWorldChangeEvent(new Events\CustomAlertsWorldChangeEvent($player, $origin, $target));
    			}
    		}
    		if(CustomAlertsAPI::getAPI()->getWorldChangeMessage() != ""){
    			Server::getInstance()->broadcastMessage(CustomAlertsAPI::getAPI()->getWorldChangeMessage());
    		}
    	}
    }
    
    public function onPlayerDeath(PlayerDeathEvent $event){
    	$player = $event->getEntity();
    	CustomAlertsAPI::getAPI()->setDeathMessage($event->getDeathMessage());
    	if($player instanceof Player){
    		$cause = $player->getLastDamageCause();
    		if(CustomAlertsAPI::getAPI()->isDeathHidden($cause)){
    			CustomAlertsAPI::getAPI()->setDeathMessage("");
    		}else{
    			//Check if Death message is custom
    			if(CustomAlertsAPI::getAPI()->isDeathCustom($cause)){
    				CustomAlertsAPI::getAPI()->setDeathMessage(CustomAlertsAPI::getAPI()->getDefaultDeathMessage($player, $cause));
    			}
    		}
    	    //Extensions Events
    		//HIGHEST
    		foreach(CustomAlertsAPI::getAPI()->getAllExtensions(CustomAlertsAPI::PRIORITY_HIGHEST) as $extension){
    			if($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()) && method_exists($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()), "onCustomAlertsDeathEvent")){
    				$this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName())->onCustomAlertsDeathEvent(new Events\CustomAlertsDeathEvent($player, $cause));
    			}
    		}
    		//HIGH
    		foreach(CustomAlertsAPI::getAPI()->getAllExtensions(CustomAlertsAPI::PRIORITY_HIGH) as $extension){
    			if($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()) && method_exists($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()), "onCustomAlertsDeathEvent")){
    				$this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName())->onCustomAlertsDeathEvent(new Events\CustomAlertsDeathEvent($player, $cause));
    			}
    		}
    		//NORMAL
    		foreach(CustomAlertsAPI::getAPI()->getAllExtensions(CustomAlertsAPI::PRIORITY_NORMAL) as $extension){
    			if($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()) && method_exists($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()), "onCustomAlertsDeathEvent")){
    				$this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName())->onCustomAlertsDeathEvent(new Events\CustomAlertsDeathEvent($player, $cause));
    			}
    		}
    		//LOW
    		foreach(CustomAlertsAPI::getAPI()->getAllExtensions(CustomAlertsAPI::PRIORITY_LOW) as $extension){
    			if($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()) && method_exists($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()), "onCustomAlertsDeathEvent")){
    				$this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName())->onCustomAlertsDeathEvent(new Events\CustomAlertsDeathEvent($player, $cause));
    			}
    		}
    		//LOWEST
    		foreach(CustomAlertsAPI::getAPI()->getAllExtensions(CustomAlertsAPI::PRIORITY_LOWEST) as $extension){
    			if($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()) && method_exists($this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName()), "onCustomAlertsDeathEvent")){
    				$this->plugin->getServer()->getPluginManager()->getPlugin($extension->getName())->onCustomAlertsDeathEvent(new Events\CustomAlertsDeathEvent($player, $cause));
    			}
    		}
    		$event->setDeathMessage(CustomAlertsAPI::getAPI()->getDeathMessage());
    	}
    }
	
}
?>
