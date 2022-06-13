<?php

namespace CustomAlerts\Commands;

use pocketmine\command\Command;
use CustomAlerts\CustomAlerts;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

Class Commands extends Command{

    private $plugin;

    public function __construct(CustomAlerts $plugin)
    {
        parent::__construct("customalerts", "CustomAlerts commands.", null, ["calerts"]);
        $this->plugin = $plugin;

    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(isset($args[0])){
            switch(strtolower($args[0])){
                case "help":
                    if($sender->hasPermission("customalerts.help")){
						$sender->sendMessage(TextFormat::colorize("&b-- &aAvailable Commands &b--"));
						$sender->sendMessage(TextFormat::colorize("&d/calerts help &b-&a Show help about this plugin"));
						$sender->sendMessage(TextFormat::colorize("&d/calerts info &b-&a Show info about this plugin"));
						$sender->sendMessage(TextFormat::colorize("&d/calerts reload &b-&a Reload the config"));
					}else{
						$sender->sendMessage(TextFormat::colorize("&cYou don't have permissions to use this command"));
					}
                break;
                case "info":
                    if($sender->hasPermission("customalerts.info")){
			            $sender->sendMessage(TextFormat::colorize(CustomAlerts::PREFIX . "&aCustomAlerts &dv" . $this->plugin->getDescription()->getVersion() . "&a developed by &dEvolSoft §r§aand updated by §dKanekiLeChomeur "));
			            $sender->sendMessage(TextFormat::colorize(CustomAlerts::PREFIX . "&aWebsite &d" . $this->plugin->getDescription()->getWebsite()));
			        }else{
						$sender->sendMessage(TextFormat::colorize("&cYou don't have permissions to use this command"));
					}
                break;
                case "reload":
                    if($sender->hasPermission("customalerts.reload")){
			            $this->plugin->reloadConfig();
			            $this->plugin->cfg = $this->plugin->getConfig()->getAll();
			            $sender->sendMessage(TextFormat::colorize(CustomAlerts::PREFIX . "&aConfiguration Reloaded."));
			        }else{
						$sender->sendMessage(TextFormat::colorize("&cYou don't have permissions to use this command"));
					}
                break;
                default:
                if($sender->hasPermission("customalerts")){
                    $sender->sendMessage(TextFormat::colorize(CustomAlerts::PREFIX . "&cSubcommand &a" . $args[0] . " &cnot found. Use &a/calerts help &cto show available commands"));
                }else{
                    $sender->sendMessage(TextFormat::colorize("&cYou don't have permissions to use this command"));	
                }
                break;
            }
        }else{
            if($sender->hasPermission("customalerts")){
                $sender->sendMessage(TextFormat::colorize(CustomAlerts::PREFIX . "&cSubcommand &a" . $args[0] . " &cnot found. Use &a/calerts help &cto show available commands"));
            }else{
                $sender->sendMessage(TextFormat::colorize("&cYou don't have permissions to use this command"));	
            }
        }
    }

}
