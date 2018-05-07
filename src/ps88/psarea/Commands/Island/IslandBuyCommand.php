<?php
    namespace ps88\psarea\Commands\Island;

    use nlog\StormCore\StormPlayer;
    use pocketmine\command\Command;
    use pocketmine\command\CommandSender;
    use pocketmine\Player;
    use pocketmine\Server;
    use ps88\psarea\Events\LandBuyEvent;
    use ps88\psarea\Loaders\Island\IslandLoader;
    use ps88\psarea\MoneyTranslate\MoneyTranslator;
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
         * @return bool
         */
        public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
            if (!$sender instanceof Player) {
                $sender->sendMessage("Only Player Can Buy this.");
                return \true;
            }
            if (!isset($args[0])) {
                $sender->sendMessage($this->getUsage());
                return \true;
            }
            if (($a = $this->owner->islandloader->getAreaById($args[0])) == \null) {
                $sender->sendMessage("Doesn't Exist");
                return \true;
            }
            if ($a->owner !== \null) {
                $sender->sendMessage("Owner already Exist");
                return \true;
            }
            if (count($this->owner->islandloader->getAreasByOwner($sender->getName())) >= IslandLoader::Maximum_Lands) {
                $sender->sendMessage("You already have maximum islands");
                return \true;
            }
            if (MoneyTranslator::getInstance()->getMoney($sender) < IslandLoader::Land_Price) {
                $sender->sendMessage("You need 30000$ to buy");
                return \true;
            }
            Server::getInstance()->getPluginManager()->callEvent($ev = new LandBuyEvent($a, $sender));
            if ($ev->isCancelled()) {
                $sender->sendMessage("Cancelled by Plugin");
                return \true;
            }
            $a->setOwner($sender);
            MoneyTranslator::getInstance()->reduceMoney($sender, IslandLoader::Land_Price);
            $sender->sendMessage("You bought {$args[0]} island");
            $nm = MoneyTranslator::getInstance()->getMoney($sender);
            $sender->sendMessage("Your money now : {$nm}");
            return \true;
        }
    }