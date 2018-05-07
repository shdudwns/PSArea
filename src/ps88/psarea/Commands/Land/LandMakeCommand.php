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

    class LandMakeCommand extends Command {

        /** @var PSAreaMain */
        private $owner;

        /**
         * LandMakeCommand constructor.
         * @param string $name
         * @param PSAreaMain $owner
         * @param string $description
         * @param string|null $usageMessage
         * @param array $aliases
         */
        public function __construct(PSAreaMain $owner, string $name = "makeland", string $description = "Make land", string $usageMessage = "/makeland [id]", $aliases = ['Id']) {
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
            if ($sender->getLevel()->getName() == "island" or $sender->getLevel()->getName() == "skyland" or $sender->getLevel()->getName() == "field"){
                $sender->sendMessage("You can't make Land at {$sender->getLevel()->getName()} Level");
                return \true;
            }
            if ($this->owner->landloader->getAreaByPosition($sender->asPosition()) !== \null){
                $sender->sendMessage("Exist");
                return \true;
            }
            $this->owner->landloader->startRegister($sender, LandLoader::$landcount++, $sender->getLevel());
            $sender->sendMessage("Pls touch 2 points");
            return \true;
        }
    }