<?php
    namespace ps88\psarea\Commands\Island;

    use nlog\StormCore\StormPlayer;
    use pocketmine\command\Command;
    use pocketmine\command\CommandSender;
    use pocketmine\Server;
    use ps88\psarea\Events\LandBuyEvent;
    use ps88\psarea\Loaders\Island\IslandLoader;
    use ps88\psarea\PSAreaMain;

    class IslandBuyCommand extends Command {

        /** @var PSAreaMain */
        private $owner;

        /**
         * IslandBuyCommand constructor.
         * @param string $name
         * @param PSAreaMain $owner
         * @param string $description
         * @param string|null $usageMessage
         * @param array $aliases
         */
        public function __construct(PSAreaMain $owner, string $name = "buyisland", string $description = "Buy Island", string $usageMessage = "/buyisland [id]", $aliases = ['Id']) {
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
                $sender->sendMessage("Only Player Can Buy this.");
                return;
            }
            if (!isset($args[0])) {
                $sender->sendMessage($this->getUsage());
                return;
            }
            if (($a = $this->owner->islandloader->getAreaById($args[0])) == \null) {
                $sender->sendMessage("Doesn't Exist");
                return;
            }
            if ($a->owner !== \null) {
                $sender->sendMessage("Owner already Exist");
                return;
            }
            if (count($this->owner->islandloader->getAreasByOwner($sender->getName())) >= IslandLoader::Maximum_Lands) {
                $sender->sendMessage("You already have maximum islands");
                return;
            }
            if ($sender->getMoney() < IslandLoader::Land_Price) {
                $sender->sendMessage("You need 30000$ to buy");
                return;
            }
            Server::getInstance()->getPluginManager()->callEvent($ev = new LandBuyEvent($a, $sender));
            if ($ev->isCancelled()) {
                $sender->sendMessage("Cancelled by Plugin");
                return;
            }
            $a->setOwner($sender);
            $sender->reduceMoney(IslandLoader::Land_Price);
            $sender->sendMessage("You bought {$args[0]} island");
            $sender->sendMessage("Your money now : {$sender->getMoney()}");
            return;
        }
    }