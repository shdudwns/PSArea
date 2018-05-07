<?php
    namespace ps88\psarea\Commands\Island;

    use nlog\StormCore\StormPlayer;
    use pocketmine\command\Command;
    use pocketmine\command\CommandSender;
    use pocketmine\Player;
    use pocketmine\Server;
    use ps88\psarea\Loaders\Island\IslandLoader;
    use ps88\psarea\PSAreaMain;

    class IslandAddShareCommand extends Command {

        /** @var PSAreaMain */
        private $owner;

        /**
         * IslandAddShareCommand constructor.
         * @param string $name
         * @param PSAreaMain $owner
         * @param string $description
         * @param string|null $usageMessage
         * @param array $aliases
         */
        public function __construct(PSAreaMain $owner, string $name = "addislandshare", string $description = "Add Island Shared Player", string $usageMessage = "/addislandshare [player] [id]", $aliases = ['Player', 'Id']) {
            parent::__construct($name, $description, $usageMessage, $aliases);
            $this->owner = $owner;
        }

        /**
         * @param CommandSender $sender
         * @param string $commandLabel
         * @param string[] $args
         *
         * @return bool
         */
        public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
            if (!$sender instanceof Player) {
                $sender->sendMessage("Only Player Can see this.");
                return \true;
            }
            $a = (!isset($args[1])) ? $this->owner->islandloader->getAreaByVector3($sender) : $this->owner->islandloader->getAreaById($args[1]);
            if ($a == \null) {
                $sender->sendMessage("Not Registered");
                return \true;
            }
            if ($a->owner->getName() !== $sender->getName()) {
                $sender->sendMessage("It's not your island");
                return \true;
            }
            if (!isset($args[1])) {
                $sender->sendMessage($this->getUsage());
                return \true;
            }
            $pl = Server::getInstance()->getPlayer($args[0]);
            if ($pl == \null) {
                $sender->sendMessage("Doesn't exist");
                return \true;
            }
            $a->addShare($pl);
            $sender->sendMessage("You add {$pl->getName()} at {$id} island");
            return \true;
        }
    }