<?php

/*
 * CustomAlerts (v1.6) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: http://www.evolsoft.tk
 * Date: 28/05/2015 04:29 PM (UTC)
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
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\protocol\DataPacket;
use pocketmine\network\protocol\Info;
use pocketmine\Player;
use pocketmine\Server;

use CustomAlerts\Events\CustomAlertsDeathEvent;
use CustomAlerts\Events\CustomAlertsFullServerKickEvent;
use CustomAlerts\Events\CustomAlertsJoinEvent;
use CustomAlerts\Events\CustomAlertsMotdUpdateEvent;
use CustomAlerts\Events\CustomAlertsOutdatedClientKickEvent;
use CustomAlerts\Events\CustomAlertsOutdatedServerKickEvent;
use CustomAlerts\Events\CustomAlertsQuitEvent;
use CustomAlerts\Events\CustomAlertsWhitelistKickEvent;
use CustomAlerts\Events\CustomAlertsWorldChangeEvent;

class EventListener implements Listener {
	
	public function __construct(CustomAlerts $plugin){
        $this->plugin = $plugin;
    }
    
    public function onReceivePacket(DataPacketReceiveEvent $event){
    	$player = $event->getPlayer();
    	$packet = $event->getPacket();
    	if($packet->pid() == Info::LOGIN_PACKET){
    		if($packet->protocol1 < Info::CURRENT_PROTOCOL){
    			//Check if outdated client message is custom
    			if(CustomAlerts::getAPI()->isOutdatedClientMessageCustom()){
    				CustomAlerts::getAPI()->setOutdatedClientMessage(CustomAlerts::getAPI()->getDefaultOutdatedClientMessage($player));
    			}
    			//Outdated Client Kick Event
    			$this->plugin->getServer()->getPluginManager()->callEvent(new CustomAlertsOutdatedClientKickEvent($player));
    			//Check if Outdated Client message is not empty
    			if(CustomAlerts::getAPI()->getOutdatedClientMessage() != null){
    				$player->close("", CustomAlerts::getAPI()->getOutdatedClientMessage());
    				$event->setCancelled(true);
    			}
    		}elseif($packet->protocol1 > Info::CURRENT_PROTOCOL){
    			//Check if outdated server message is custom
    			if(CustomAlerts::getAPI()->isOutdatedServerMessageCustom()){
    				CustomAlerts::getAPI()->setOutdatedServerMessage(CustomAlerts::getAPI()->getDefaultOutdatedServerMessage($player));
    			}
    			//Outdated Server Kick Event
    			$this->plugin->getServer()->getPluginManager()->callEvent(new CustomAlertsOutdatedServerKickEvent($player));
    			//Check if Outdated Server message is not empty
    			if(CustomAlerts::getAPI()->getOutdatedServerMessage() != null){
    				$player->close("", CustomAlerts::getAPI()->getOutdatedServerMessage());
    				$event->setCancelled(true);
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
    		if(!$this->plugin->getServer()->isWhitelisted($event->getPlayer()->getName())){
    			//Check if Whitelist message is custom
    			if(CustomAlerts::getAPI()->isWhitelistMessageCustom()){
    				CustomAlerts::getAPI()->setWhitelistMessage(CustomAlerts::getAPI()->getDefaultWhitelistMessage($player));
    			}
    			//Whitelist Kick Event
    			$this->plugin->getServer()->getPluginManager()->callEvent(new CustomAlertsWhitelistKickEvent($player));
    			//Check if Whitelist message is not empty
    			if(CustomAlerts::getAPI()->getWhitelistMessage() != null){
    				$player->close("", CustomAlerts::getAPI()->getWhitelistMessage());
    				$event->setCancelled(true);
    			}
    		}
    	}else{
    		//Check if Full Server message is custom
    		if(CustomAlerts::getAPI()->isFullServerMessageCustom()){
    			CustomAlerts::getAPI()->setFullServerMessage(CustomAlerts::getAPI()->getDefaultFullServerMessage($player));
    		}
    		//Full Server Kick Event
    		$this->plugin->getServer()->getPluginManager()->callEvent(new CustomAlertsFullServerKickEvent($player));
    		//Check if Full Server message is not empty
    		if(CustomAlerts::getAPI()->getFullServerMessage() != null){
    			$player->close("", CustomAlerts::getAPI()->getFullServerMessage());
    			$event->setCancelled(true);
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
    	//Check if Motd message is custom
    	if(CustomAlerts::getAPI()->isMotdCustom()){
    		CustomAlerts::getAPI()->setMotdMessage(CustomAlerts::getAPI()->getDefaultMotdMessage());
    	}else{
    		CustomAlerts::getAPI()->setMotdMessage($this->plugin->translateColors("&", $this->plugin->getServer()->getMotd()));
    	}
    	//Motd Update Event
    	$this->plugin->getServer()->getPluginManager()->callEvent(new CustomAlertsMotdUpdateEvent($this->plugin->getServer()->getMotd()));
    	$this->plugin->getServer()->getNetwork()->setName(CustomAlerts::getAPI()->getMotdMessage());
    	//Join Message
    	$status = 0;
    	CustomAlerts::getAPI()->setJoinMessage($event->getJoinMessage());
    	//Get First Join
    	if(CustomAlerts::getAPI()->hasJoinedFirstTime($player)){
    		//Register FirstJoin
    		CustomAlerts::getAPI()->registerFirstJoin($player);
    		//Check if FirstJoin message is enabled
    		if(CustomAlerts::getAPI()->isDefaultFirstJoinMessageEnabled()){
    			CustomAlerts::getAPI()->setJoinMessage(CustomAlerts::getAPI()->getDefaultFirstJoinMessage($player));
    			$status = 1;
    		}
    	}
    	//Default Join Message
    	if($status == 0){
    		//Check if Join message is hidden
    		if(CustomAlerts::getAPI()->isDefaultJoinMessageHidden()){
    			CustomAlerts::getAPI()->setJoinMessage("");
    		}else{
    			//Check if Join message is custom
    			if(CustomAlerts::getAPI()->isDefaultJoinMessageCustom()){
    				CustomAlerts::getAPI()->setJoinMessage(CustomAlerts::getAPI()->getDefaultJoinMessage($player));
    			}
    		}
    	}
    	//Join Event
    	$this->plugin->getServer()->getPluginManager()->callEvent(new CustomAlertsJoinEvent($player, $event->getJoinMessage()));
    	$event->setJoinMessage(CustomAlerts::getAPI()->getJoinMessage());
    }
    
    /**
     * @param PlayerQuitEvent $event
     *
     * @priority HIGHEST
     */
    public function onPlayerQuit(PlayerQuitEvent $event){
    	 $player = $event->getPlayer();
    	 //Motd Update
    	 if(CustomAlerts::getAPI()->isMotdCustom()){
    	 	CustomAlerts::getAPI()->setMotdMessage(CustomAlerts::getAPI()->getDefaultMotdMessage());
    	 }else{
    	 	CustomAlerts::getAPI()->setMotdMessage($this->plugin->translateColors("&", $this->plugin->getServer()->getMotd()));
    	 }
    	 //Motd Update Event
    	 $this->plugin->getServer()->getPluginManager()->callEvent(new CustomAlertsMotdUpdateEvent($this->plugin->getServer()->getMotd()));
    	 $this->plugin->getServer()->getNetwork()->setName(CustomAlerts::getAPI()->getMotdMessage());
    	 CustomAlerts::getAPI()->setQuitMessage($event->getQuitMessage());
    	 //Check if Quit message is hidden
    	 if(CustomAlerts::getAPI()->isQuitHidden()){
    	 	CustomAlerts::getAPI()->setQuitMessage("");
    	 }else{
    	 	//Check if Quit message is custom
    	 	if(CustomAlerts::getAPI()->isQuitCustom()){
    	 		CustomAlerts::getAPI()->setQuitMessage(CustomAlerts::getAPI()->getDefaultQuitMessage($player));
    	 	}
    	 }
    	 //Quit Event
    	 $this->plugin->getServer()->getPluginManager()->callEvent(new CustomAlertsQuitEvent($player, $event->getQuitMessage()));
    	 $event->setQuitMessage(CustomAlerts::getAPI()->getQuitMessage());
    }
    
    public function onWorldChange(EntityLevelChangeEvent $event){
    	$entity = $event->getEntity();
    	CustomAlerts::getAPI()->setWorldChangeMessage("");
    	//Check if the Entity is a Player
    	if($entity instanceof Player){
    		$player = $entity;
    		$origin = $event->getOrigin();
    		$target = $event->getTarget();
    		//Check if Default WorldChange Message is enabled
    		if(CustomAlerts::getAPI()->isDefaultWorldChangeMessageEnabled()){
    			CustomAlerts::getAPI()->setWorldChangeMessage(CustomAlerts::getAPI()->getDefaultWorldChangeMessage($player, $origin, $target));
    		}
    	    //WorldChange Event
    	    $this->plugin->getServer()->getPluginManager()->callEvent(new CustomAlertsWorldChangeEvent($player, $origin, $target));
    		if(CustomAlerts::getAPI()->getWorldChangeMessage() != ""){
    			Server::getInstance()->broadcastMessage(CustomAlerts::getAPI()->getWorldChangeMessage());
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
    	CustomAlerts::getAPI()->setDeathMessage($event->getDeathMessage());
    	if($player instanceof Player){
    		$cause = $player->getLastDamageCause();
    		if(CustomAlerts::getAPI()->isDeathHidden($cause)){
    			CustomAlerts::getAPI()->setDeathMessage("");
    		}else{
    			//Check if Death message is custom
    			if(CustomAlerts::getAPI()->isDeathCustom($cause)){
    				CustomAlerts::getAPI()->setDeathMessage(CustomAlerts::getAPI()->getDefaultDeathMessage($player, $cause));
    			}
    		}
            //Death Event
    	    $this->plugin->getServer()->getPluginManager()->callEvent(new CustomAlertsDeathEvent($player, $cause));
    		$event->setDeathMessage(CustomAlerts::getAPI()->getDeathMessage());
    	}
    }
	
}
?>
