<?php

/*
 * CustomAlerts v2.0 by EvolSoft
 * Developer: Flavius12
 * Website: https://www.evolsoft.tk
 * Copyright (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/CustomAlerts/blob/master/LICENSE)
 */

namespace CustomAlerts\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;
use pocketmine\plugin\PluginOwnedTrait;
use pocketmine\utils\TextFormat;

use CustomAlerts\CustomAlerts;

class Commands extends Command implements PluginOwned {

	use PluginOwnedTrait;

	private CustomAlerts $plugin;

	public function __construct(CustomAlerts $plugin){
		parent::__construct("customalerts");
       	$this->plugin = $plugin;
       	$this->setPermission("customalerts.help");
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args){
		if(isset($args[0])){
			$args[0] = strtolower($args[0]);
			switch($args[0]){
			    case "help":
			        goto help;
			    case "info":
			        if($sender->hasPermission("customalerts.info")){
			            $sender->sendMessage(TextFormat::colorize(CustomAlerts::PREFIX . "&aCustomAlerts &dv" . $this->plugin->getDescription()->getVersion() . "&a developed by &dEvolSoft"));
			            $sender->sendMessage(TextFormat::colorize(CustomAlerts::PREFIX . "&aWebsite &d" . $this->plugin->getDescription()->getWebsite()));
			            break;
			        }
			        $sender->sendMessage(TextFormat::colorize("&cYou don't have permissions to use this command"));
			        break;
			    case "reload":
			        if($sender->hasPermission("customalerts.reload")){
			            $this->plugin->reloadConfig();
			            $this->plugin->cfg = $this->plugin->getConfig()->getAll();
			            $sender->sendMessage(TextFormat::colorize(CustomAlerts::PREFIX . "&aConfiguration Reloaded."));
			            break;
			        }
			        $sender->sendMessage(TextFormat::colorize("&cYou don't have permissions to use this command"));
			        break;
			    default:
			        if($sender->hasPermission("customalerts")){
			            $sender->sendMessage(TextFormat::colorize(CustomAlerts::PREFIX . "&cSubcommand &a" . $args[0] . " &cnot found. Use &a/calerts help &cto show available commands"));
			            break;
			        }
			        $sender->sendMessage(TextFormat::colorize("&cYou don't have permissions to use this command"));
			        break;
			}
			return;
		}
		help:
		if($sender->hasPermission("customalerts.help")){
		    $sender->sendMessage(TextFormat::colorize("&b-- &aAvailable Commands &b--"));
		    $sender->sendMessage(TextFormat::colorize("&d/calerts help &b-&a Show help about this plugin"));
		    $sender->sendMessage(TextFormat::colorize("&d/calerts info &b-&a Show info about this plugin"));
		    $sender->sendMessage(TextFormat::colorize("&d/calerts reload &b-&a Reload the config"));
		}else{
		    $sender->sendMessage(TextFormat::colorize("&cYou don't have permissions to use this command"));
		}
    	return;
    }
}
