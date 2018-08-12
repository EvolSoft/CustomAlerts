<?php

/*
 * CustomAlerts v2.0 by EvolSoft
 * Developer: Flavius12
 * Website: https://www.evolsoft.tk
 * Copyright (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/CustomAlerts/blob/master/LICENSE)
 */

namespace CustomAlerts;

use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\Player;
use pocketmine\Server;

use CustomAlerts\Events\CustomAlertsDeathEvent;
use CustomAlerts\Events\CustomAlertsFullServerKickEvent;
use CustomAlerts\Events\CustomAlertsJoinEvent;
use CustomAlerts\Events\CustomAlertsOutdatedClientKickEvent;
use CustomAlerts\Events\CustomAlertsOutdatedServerKickEvent;
use CustomAlerts\Events\CustomAlertsQuitEvent;
use CustomAlerts\Events\CustomAlertsWhitelistKickEvent;
use CustomAlerts\Events\CustomAlertsWorldChangeEvent;

class EventListener implements Listener {
	
	public function __construct(CustomAlerts $plugin){
        $this->plugin = $plugin;
    }
    
    /**
     * @param DataPacketReceiveEvent $event
     *
     * @priority HIGHEST
     */
    public function onReceivePacket(DataPacketReceiveEvent $event){
    	$player = $event->getPlayer();
    	$packet = $event->getPacket();
    	if($packet instanceof LoginPacket){
    	    if($packet->protocol < ProtocolInfo::CURRENT_PROTOCOL){
    	        //Outdated Client message
    	        $cevent = new CustomAlertsOutdatedClientKickEvent($player);
    	        if($this->plugin->isOutdatedClientMessageCustom()){
    	            $cevent->setMessage($this->plugin->getOutdatedClientMessage($player));
    	        }
    	        $this->plugin->getServer()->getPluginManager()->callEvent($cevent);
    	        if($cevent->getMessage() != ""){
    	            $player->close($cevent->getMessage(), $cevent->getMessage());
    	            $event->setCancelled(true);
    	            return;
    	        }
    	    }else if($packet->protocol > ProtocolInfo::CURRENT_PROTOCOL){
    	        //Outdated Server message
    	        $cevent = new CustomAlertsOutdatedServerKickEvent($player);
    	        if($this->plugin->isOutdatedServerMessageCustom()){
    	            $cevent->setMessage($this->plugin->getOutdatedServerMessage($player));
    	        }
    	        $this->plugin->getServer()->getPluginManager()->callEvent($cevent);
    	        if($cevent->getMessage() != ""){
    	            $player->close($cevent->getMessage(), $cevent->getMessage());
    	            $event->setCancelled(true);
    	            return;
    	        }
    	    }
    	}
    }
    
    /**
     * @param PlayerPreLoginEvent $event
     *
     * @priority HIGHEST
     */
    public function onPlayerPreLogin(PlayerPreLoginEvent $event){
    	$player = $event->getPlayer();
    	if(count($this->plugin->getServer()->getOnlinePlayers()) - 1 < $this->plugin->getServer()->getMaxPlayers()){
    	    //Whitelist Message
    		if(!$this->plugin->getServer()->isWhitelisted($player->getName())){
    		    $cevent = new CustomAlertsWhitelistKickEvent($player);
    			if($this->plugin->isWhitelistMessageCustom()){
    				$cevent->setMessage($this->plugin->getWhitelistMessage($player));
    			}
    			$this->plugin->getServer()->getPluginManager()->callEvent($cevent);
    			if($cevent->getMessage() != ""){
    				$player->close("", $cevent->getMessage());
    				$event->setCancelled(true);
    				return;
    			}
    		}
    	}else{
    		//Full Server Message
    		$cevent = new CustomAlertsFullServerKickEvent($player);
    		if($this->plugin->isFullServerMessageCustom()){
    			$cevent->setMesssage($this->plugin->getFullServerMessage($player));
    		}    		
    		$this->plugin->getServer()->getPluginManager()->callEvent($cevent);
    		if($cevent->getMessage() != ""){
    			$player->close("", $cevent->getMessage());
    			$event->setCancelled(true);
    			return;
    		}
    	}
    }
    
    /**
     * @param PlayerJoinEvent $event
     *
     * @priority HIGHEST
     */
    public function onPlayerJoin(PlayerJoinEvent $event){
    	$player = $event->getPlayer();
    	//Motd Update
    	$this->plugin->updateMotd();
    	//Join Message
    	$cevent = new CustomAlertsJoinEvent($player);
    	if(!$player->hasPlayedBefore() && $this->plugin->isFirstJoinMessageEnabled()){
    		$cevent->setMessage($this->plugin->getFirstJoinMessage($player));
    	}else if($this->plugin->isJoinMessageHidden()){
    	    $cevent->setMessage("");
    	}else if($this->plugin->isJoinMessageCustom()){
    	    $cevent->setMessage($this->plugin->getJoinMessage($player));
    	}else{
    	    $cevent->setMessage($event->getJoinMessage());
    	}
    	$this->plugin->getServer()->getPluginManager()->callEvent($cevent);
    	$event->setJoinMessage($cevent->getMessage());
    }
    
    /**
     * @param PlayerQuitEvent $event
     *
     * @priority HIGHEST
     */
    public function onPlayerQuit(PlayerQuitEvent $event){
    	 $player = $event->getPlayer();
    	 //Motd Update
    	 $this->plugin->updateMotd();
    	 //Quit Message
    	 $cevent = new CustomAlertsQuitEvent($player);
    	 if($this->plugin->isQuitMessageHidden()){
    	     $cevent->setMessage("");
    	 }else if($this->plugin->isQuitMessageCustom()){
    	     $cevent->setMessage($this->plugin->getQuitMessage($player));
    	 }else{
    	     $cevent->setMessage($event->getQuitMessage());
    	 }
    	 $this->plugin->getServer()->getPluginManager()->callEvent($cevent);
    	 $event->setQuitMessage($cevent->getMessage());
    }
    
    /**
     * @param EntityLevelChangeEvent $event
     *
     * @priority HIGHEST
     */
    public function onWorldChange(EntityLevelChangeEvent $event){
    	$entity = $event->getEntity();
    	//Check if the Entity is a Player
    	if($entity instanceof Player){
    		$player = $entity;
    		$origin = $event->getOrigin();
    		$target = $event->getTarget();
    		$cevent = new CustomAlertsWorldChangeEvent($player, $origin, $target);
    		if($this->plugin->isWorldChangeMessageEnabled()){
    			$cevent->setMessage($this->plugin->getWorldChangeMessage($player, $origin, $target));
    		}else{
    		    $cevent->setMessage("");
    		}
    	    $this->plugin->getServer()->getPluginManager()->callEvent($cevent);
    		if($cevent->getMessage() != ""){
    			Server::getInstance()->broadcastMessage($cevent->getMessage());
    		}
    	}
    }
    
    
    /**
     * @param PlayerDeathEvent $event
     *
     * @priority HIGHEST
     */
    public function onPlayerDeath(PlayerDeathEvent $event){
    	$player = $event->getEntity();
    	if($player instanceof Player){
    	    $cause = $player->getLastDamageCause();
    	    $cevent = new CustomAlertsDeathEvent($player, $cause);
    		if($this->plugin->isDeathMessageHidden($cause)){
    			$cevent->setMessage("");
    		}else if($this->plugin->isDeathMessageCustom($cause)){
    		    $cevent->setMessage($this->plugin->getDeathMessage($player, $cause));
    		}else{
    		    $cevent->setMessage($event->getDeathMessage());
    		}
    	    $this->plugin->getServer()->getPluginManager()->callEvent($cevent);
    		$event->setDeathMessage($cevent->getMessage());
    	}
    }
}