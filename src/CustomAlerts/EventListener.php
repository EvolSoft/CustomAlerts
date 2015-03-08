<?php

/*
 * CustomAlerts (v1.1) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: http://www.evolsoft.tk
 * Date: 29/12/2014 09:28 AM (UTC)
 * Copyright & License: (C) 2014 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/CustomAlerts/blob/master/LICENSE)
 */

namespace CustomAlerts;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\permission\Permission;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class EventListener extends PluginBase implements Listener{
	
	public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }
    
    public function onPlayerJoin(PlayerJoinEvent $event){
    	$player = $event->getPlayer();
    	$status = 0;
    	//Get First Join
    	if($this->plugin->hasJoinedFirstTime($player)){
    		//Register FirstJoin
    		$this->plugin->registerFirstJoin($player);
    		//Check if FirstJoin message is enabled
    		if($this->plugin->isFirstJoinEnabled()){
    			$event->setJoinMessage($this->plugin->getFirstJoinMessage($player));
    			$status = 1;
    		}
    	}
    	//Default Join Message
    	if($status == 0){
    		//Check if Join message is hidden
    		if($this->plugin->isJoinHidden()){
    			$event->setJoinMessage("");
    		}else{
    			//Check if Join message is custom
    			if($this->plugin->isJoinCustom()){
    				$event->setJoinMessage($this->plugin->getJoinMessage($player));
    			}
    		}
    	}
    }
    
    public function onPlayerQuit(PlayerQuitEvent $event){
    	 $player = $event->getPlayer();
    	 //Check if Quit message is hidden
    	 if($this->plugin->isQuitHidden()){
    	 	$event->setQuitMessage("");
    	 }else{
    	 	//Check if Join message is custom
    	 	if($this->plugin->isQuitCustom()){
    	 		$event->setQuitMessage($this->plugin->getQuitMessage($player));
    	 	}
    	 }
    }
    
    public function onPlayerDeath(PlayerDeathEvent $event){
    	$player = $event->getEntity();
    	if($player instanceof Player){
    		$cause = $event->getEntity()->getLastDamageCause()->getCause();
    		//Check if Quit message is hidden
    		if($this->plugin->isDeathHidden()){
    			$event->setDeathMessage("");
    		}else{
    			//Check if Death message is custom
    			if($this->plugin->isDeathCustom()){
    				if($cause == 1){
    					//Get Killer Entity
    					$killer = $event->getEntity()->getLastDamageCause()->getDamager();
    					//Get if the killer is a player
    					if($killer instanceof Player){
    						$event->setDeathMessage($this->plugin->getKillMessage($player, $killer));
    					}else{
    						//Default Message (Entities not implemented yet in PocketMine)
    						$event->setDeathMessage($this->plugin->getDeathMessage($player));
    					}
    				}elseif($cause == 3){ //Suffocation
    					$event->setDeathMessage($this->plugin->getDeathMessage($player, 3));
    				}elseif($cause == 4){ //Fall
    					$event->setDeathMessage($this->plugin->getDeathMessage($player, 4));
    				}elseif($cause == 5){ //Fire
    					$event->setDeathMessage($this->plugin->getDeathMessage($player, 5));
    				}elseif($cause == 7){ //Lava
    					$event->setDeathMessage($this->plugin->getDeathMessage($player, 7));
    				}elseif($cause == 8){ //Drowning
    					$event->setDeathMessage($this->plugin->getDeathMessage($player, 8));
    				}elseif($cause == 9){ //Explosion
    					$event->setDeathMessage($this->plugin->getDeathMessage($player, 9));
    				}else{ //Default message
    					$event->setDeathMessage($this->plugin->getDeathMessage($player));
    				}
    			}
    		}
    	}
    }
    
    public function onWorldChange(EntityLevelChangeEvent $event){
    	//Check if WorldChange Message is enabled
    	if($this->plugin->isWorldChangeEnabled()){
    		$player = $event->getEntity();
    		//Check if the Entity is a Player
    		if($player instanceof Player){
    			$origin = $event->getOrigin();
    			$target = $event->getTarget();
    			$this->getServer()->broadcastMessage($this->plugin->getWorldChangeMessage($player, $origin, $target));
    		}
    	}
    }
	
}
    ?>
