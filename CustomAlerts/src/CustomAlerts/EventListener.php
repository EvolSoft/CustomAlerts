<?php

/*
 * CustomAlerts v2.0 by EvolSoft
 * Developer: Flavius12
 * Website: https://www.evolsoft.tk
 * Copyright (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/CustomAlerts/blob/master/LICENSE)
 */

namespace CustomAlerts;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\network\mcpe\NetworkSession;
use CustomAlerts\Events\CustomAlertsDeathEvent;
use CustomAlerts\Events\CustomAlertsFullServerKickEvent;
use CustomAlerts\Events\CustomAlertsJoinEvent;
use CustomAlerts\Events\CustomAlertsOutdatedClientKickEvent;
use CustomAlerts\Events\CustomAlertsOutdatedServerKickEvent;
use CustomAlerts\Events\CustomAlertsQuitEvent;
use CustomAlerts\Events\CustomAlertsWhitelistKickEvent;
use CustomAlerts\Events\CustomAlertsWorldChangeEvent;
use pocketmine\event\entity\EntityTeleportEvent;

class EventListener implements Listener {

	private $plugin;
	
	public function __construct(CustomAlerts $plugin){
        $this->plugin = $plugin;
    }
    
    /**
     * @param DataPacketReceiveEvent $event
     *
     * @priority HIGHEST
     */
    public function onReceivePacket(DataPacketReceiveEvent $event){
	    $origin = $event->getOrigin();
    	$packet = $event->getPacket();
    	if($packet instanceof LoginPacket){
    	    if($packet->protocol < ProtocolInfo::CURRENT_PROTOCOL){
    	        //Outdated Client message
    	        $cevent = new CustomAlertsOutdatedClientKickEvent($origin);
    	        if($this->plugin->isOutdatedClientMessageCustom()){
    	            $cevent->setMessage($this->plugin->getOutdatedClientMessage());
    	        }
    	        $cevent->call();
    	        if($cevent->getMessage() != ""){
					$origin->disconnect($cevent->getMessage());
    	            $event->cancel();
    	            return;
    	        }
    	    }else if($packet->protocol > ProtocolInfo::CURRENT_PROTOCOL){
    	        //Outdated Server message
    	        $cevent = new CustomAlertsOutdatedServerKickEvent($origin);
    	        if($this->plugin->isOutdatedServerMessageCustom()){
    	            $cevent->setMessage($this->plugin->getOutdatedServerMessage());
    	        }
    	        $cevent->call();
    	        if($cevent->getMessage() != ""){
    	            $origin->disconnect($cevent->getMessage());
    	            $event->cancel();
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
    	$player = $event->getPlayerInfo();
    	if(count($this->plugin->getServer()->getOnlinePlayers()) - 1 < $this->plugin->getServer()->getMaxPlayers()){
    	    //Whitelist Message
    		if(!$this->plugin->getServer()->isWhitelisted($player->getUsername())){
    		    $cevent = new CustomAlertsWhitelistKickEvent($player);
    			if($this->plugin->isWhitelistMessageCustom()){
    				$cevent->setMessage($this->plugin->getWhitelistMessage($player));
    			}
    			$cevent->call();
    			if($cevent->getMessage() != ""){
    				$event->setKickReason(PlayerPreLoginEvent::KICK_REASON_SERVER_WHITELISTED, $cevent->getMessage());
    				return;
    			}
    		}
    	}else{
    		//Full Server Message
    		$cevent = new CustomAlertsFullServerKickEvent($player);
    		if($this->plugin->isFullServerMessageCustom()){
    			$cevent->setMesssage($this->plugin->getFullServerMessage($player));
    		}    		
    		$cevent->call();
    		if($cevent->getMessage() != ""){
    			$event->setKickReason(PlayerPreLoginEvent::KICK_REASON_SERVER_FULL, $cevent->getMessage());
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
    	$cevent->call();
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
    	 $cevent->call();
    	 $event->setQuitMessage($cevent->getMessage());
    }
    
    /**
     * @param EntityTeleportEvent $event
     *
     * @priority HIGHEST
     */
    public function onWorldChange(EntityTeleportEvent $event){
    	
		$from = $event->getFrom()->getWorld();
		$to = $event->getTo()->getWorld();
		$player = $event->getEntity();
    	if($from->getDisplayName() !== $to->getDisplayName()){
			$cevent = new CustomAlertsWorldChangeEvent($player, $from, $to);
    		if($this->plugin->isWorldChangeMessageEnabled()){
    			$cevent->setMessage($this->plugin->getWorldChangeMessage($player, $from, $to));
    		}else{
    		    $cevent->setMessage("");
    		}
    	    $cevent->call();
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
    	    $cevent->call();
    		$event->setDeathMessage($cevent->getMessage());
    	}
    }
}
