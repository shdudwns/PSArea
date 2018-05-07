<?php
    namespace ps88\psarea\Commands\Skyland;

    use nlog\StormCore\StormPlayer;
    use pocketmine\command\Command;
    use pocketmine\command\CommandSender;
    use pocketmine\Player;
    use ps88\psarea\Loaders\Skyland\SkylandLoader;
    use ps88\psarea\PSAreaMain;

    class SkylandInfoCommand extends Command {

        /** @var PSAreaMain */
        private $owner;

        /**
         * SkylandInfoCommand constructor.
         * @param string $name
         * @param PSAreaMain $owner
         * @param string $description
         * @param string|null $usageMessage
         * @param array $aliases
         */
        public function __construct(PSAreaMain $owner, string $name = "infoskyland", string $description = "Fet Skyland info", string $usageMessage = "/infoskyland [id]", $aliases = ['Id']) {
            parent::__construct($name, $description, $usageMessage, $aliases);
            $this->owner = $owner;
        }

        /**
         * @param CommandSender $sender
         * @param string $commandLabel
         * @param string[] $args
         *
         * @return mixed
         */
        public function execute(CommandSender $sender, string $commandLabel, array $args) {
            if(!isset($args[0])){
$sender->sendMessage($this->getUsage());
return true;
}
            $a = (!isset($args[0])) ? $this->owner->skylandloader->getAreaByVector3($sender) : $this->owner->skylandloader->getAreaById($args[0]);
            if ($a == \null) {
                $sender->sendMessage("Not Registered");
                return true;
            }
            $sender->sendMessage("====[{$args[0]} skyland]====");
            $owner = ($a->owner == \null) ? "None" : $a->owner->getName();
            $sender->sendMessage("Owner : {$owner}");
            $sender->sendMessage("Shares :");
            if (empty($a->getShares())) {
                $sender->sendMessage("None");
            } else {
                foreach ($a->getShares() as $share) {
                    $sender->sendMessage($share->getName());
                }
            }
            return true;
        }
    }
