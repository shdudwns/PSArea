<?php
    namespace ps88\psarea\Commands\Skyland;

    use nlog\StormCore\StormPlayer;
    use pocketmine\command\Command;
    use pocketmine\command\CommandSender;
    use pocketmine\Server;
    use ps88\psarea\Loaders\Skyland\SkylandLoader;
    use ps88\psarea\PSAreaMain;

    class SkylandGiveCommand extends Command {

        /** @var PSAreaMain */
        private $owner;

        /**
         * SkylandGiveCommand constructor.
         * @param string $name
         * @param PSAreaMain $owner
         * @param string $description
         * @param string|null $usageMessage
         * @param array $aliases
         */
        public function __construct(PSAreaMain $owner, string $name = "giveskyland", string $description = "Give Skyland to other Player", string $usageMessage = "/giveskylandshare [player] [id]", $aliases = ['Player', 'Id']) {
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
            if (!$sender instanceof StormPlayer) {
                $sender->sendMessage("Only Player Can see this.");
                return;
            }
            $id = (!isset($args[0])) ? $this->owner->skylandloader->getAreaByVector3($sender) : (int) $args[1];
            if (($a = $this->owner->skylandloader->getAreaById($id)) == \null) {
                $sender->sendMessage("Not Registered");
                return;
            }
            if (!isset($args[0])) {
                $sender->sendMessage($this->getUsage());
                return;
            }
            $pl = Server::getInstance()->getPlayer($args[0]);
            if ($pl == \null) {
                $sender->sendMessage("Doesn't exist");
                return;
            }
            if (count($this->owner->skylandloader->getAreasByOwner($pl->getName())) >= SkylandLoader::Maximum_Lands) {
                $sender->sendMessage("He has maximum Lands");
                return;
            }
            $a->setOwner($pl);
            $sender->sendMessage("Owner Changed!!");
            $pl->sendMessage("You got {$id} by {$sender->getName()}");
        }
    }