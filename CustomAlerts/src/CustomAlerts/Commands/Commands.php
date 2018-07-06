<?php

/*
 * CustomAlerts (v1.9) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: https://www.evolsoft.tk
 * Date: 13/01/2018 02:02 PM (UTC)
 * Copyright & License: (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/CustomAlerts/blob/master/LICENSE)
 */

namespace CustomAlerts\Commands;

use pocketmine\command\Command;
use pocketmine\command\PluginCommand;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\Plugin;

use CustomAlerts\CustomAlerts;

class Commands extends PluginCommand implements CommandExecutor {

	public function __construct(CustomAlerts $plugin){
       $this->plugin = $plugin;
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) : bool {
		if(isset($args[0])){
			$args[0] = strtolower($args[0]);
			switch($args[0]){
			    case "help":
			        goto help;
			    case "info":
			        if($sender->hasPermission("customalerts.info")){
			            $sender->sendMessage($this->plugin->translateColors("&", CustomAlerts::PREFIX . "&aCustomAlerts &dv" . $this->plugin->getDescription()->getVersion() . "&a developed by &dEvolSoft"));
			            $sender->sendMessage($this->plugin->translateColors("&", CustomAlerts::PREFIX . "&aWebsite &d" . $this->plugin->getDescription()->getWebsite()));
			            break;
			        }
			        $sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
			        break;
			    case "reload":
			        if($sender->hasPermission("customalerts.reload")){
			            $this->plugin->reloadConfig();
			            $this->plugin->cfg = $this->plugin->getConfig()->getAll();
			            $sender->sendMessage($this->plugin->translateColors("&", CustomAlerts::PREFIX . "&aConfiguration Reloaded."));
			            break;
			        }
			        $sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
			        break;
			    default:
			        if($sender->hasPermission("customalerts")){
			            $sender->sendMessage($this->plugin->translateColors("&",  CustomAlerts::PREFIX . "&cSubcommand &a" . $args[0] . " &cnot found. Use &a/calerts help &cto show available commands"));
			            break;
			        }
			        $sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
			        break;
			}
			return true;
		}
		help:
		if($sender->hasPermission("customalerts.help")){
			$sender->sendMessage($this->plugin->translateColors("&", "&b-- &aAvailable Commands &b--"));
			$sender->sendMessage($this->plugin->translateColors("&", "&d/calerts help &b-&a Show help about this plugin"));
			$sender->sendMessage($this->plugin->translateColors("&", "&d/calerts info &b-&a Show info about this plugin"));
			$sender->sendMessage($this->plugin->translateColors("&", "&d/calerts reload &b-&a Reload the config"));
		}else{
			$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
		}
    	return true;
    }
}
