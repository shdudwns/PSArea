<?php
    namespace ps88\psarea\Commands\Skyland;

    use nlog\StormCore\StormPlayer;
    use pocketmine\command\Command;
    use pocketmine\command\CommandSender;
    use ps88\psarea\Loaders\Skyland\SkylandLoader;
    use ps88\psarea\PSAreaMain;

    class SkylandBuyCommand extends Command {

        /** @var PSAreaMain */
        private $owner;

        /**
         * SkylandBuyCommand constructor.
         * @param string $name
         * @param PSAreaMain $owner
         * @param string $description
         * @param string|null $usageMessage
         * @param array $aliases
         */
        public function __construct(PSAreaMain $owner, string $name = "buyskyland", string $description = "Buy skyland", string $usageMessage = "/buyskyland [id]", $aliases = ['Id']) {
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
            if (($a = $this->owner->skylandloader->getAreaById($args[0])) == \null) {
                $sender->sendMessage("Doesn't Exist");
                return;
            }
            if ($a->owner !== \null) {
                $sender->sendMessage("Owner already Exist");
                return;
            }
            if (count($this->owner->skylandloader->getAreasByOwner($sender->getName())) >= SkylandLoader::Maximum_Lands) {
                $sender->sendMessage("You already have maximum Skylands");
                return;
            }
            if ($sender->getMoney() < SkylandLoader::Land_Price) {
                $sender->sendMessage("You need 30000$ to buy");
                return;
            }
            $a->setOwner($sender);
            $sender->reduceMoney(SkylandLoader::Land_Price);
            $sender->sendMessage("You bought {$args[0]} Skyland");
            $sender->sendMessage("Your money now : {$sender->getMoney()}");
            return;
        }
    }