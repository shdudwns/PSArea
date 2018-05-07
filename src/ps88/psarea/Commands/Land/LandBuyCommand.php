<?php
    namespace ps88\psarea\Commands\Land;

    use nlog\StormCore\StormPlayer;
    use pocketmine\command\Command;
    use pocketmine\command\CommandSender;
    use pocketmine\Player;
    use pocketmine\Server;
    use ps88\psarea\Events\LandBuyEvent;
    use ps88\psarea\Loaders\Land\LandLoader;
    use ps88\psarea\MoneyTranslate\MoneyTranslator;
    use ps88\psarea\PSAreaMain;

    class LandBuyCommand extends Command {

        /** @var PSAreaMain */
        private $owner;

        /**
         * LandBuyCommand constructor.
         * @param string $name
         * @param PSAreaMain $owner
         * @param string $description
         * @param string|null $usageMessage
         * @param array $aliases
         */
        public function __construct(PSAreaMain $owner, string $name = "buyland", string $description = "Buy land", string $usageMessage = "/buyland [id]", $aliases = ['Id']) {
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
            if (($a = $this->owner->landloader->getAreaById($args[0])) == \null) {
                $sender->sendMessage("Doesn't Exist");
                return \true;
            }
            if ($a->owner !== \null) {
                $sender->sendMessage("Owner already Exist");
                return \true;
            }
            if (count($this->owner->landloader->getAreasByOwner($sender->getName())) >= LandLoader::Maximum_Lands) {
                $sender->sendMessage("You already have maximum lands");
                return \true;
            }
            if (MoneyTranslator::getInstance()->getMoney($sender) < LandLoader::Land_Price) {
                $sender->sendMessage("You need 30000$ to buy");
                return \true;
            }
            Server::getInstance()->getPluginManager()->callEvent($ev = new LandBuyEvent($a, $sender));
            if ($ev->isCancelled()) {
                $sender->sendMessage("Cancelled by Plugin");
                return \true;
            }
            $a->setOwner($sender);
            MoneyTranslator::getInstance()->reduceMoney($sender, LandLoader::Land_Price);
            $sender->sendMessage("You bought {$args[0]} land");
            $nm = MoneyTranslator::getInstance()->getMoney($sender);
            $sender->sendMessage("Your money now : {$nm}");
            return \true;
        }
    }