<?php
    namespace ps88\psarea\Commands\Field;

    use nlog\StormCore\StormPlayer;
    use pocketmine\command\Command;
    use pocketmine\command\CommandSender;
    use pocketmine\Player;
    use pocketmine\Server;
    use ps88\psarea\Loaders\Field\FieldLoader;
    use ps88\psarea\PSAreaMain;

    class FieldGiveCommand extends Command {

        /** @var PSAreaMain */
        private $owner;

        /**
         * FieldGiveCommand constructor.
         * @param string $name
         * @param PSAreaMain $owner
         * @param string $description
         * @param string|null $usageMessage
         * @param array $aliases
         */
        public function __construct(PSAreaMain $owner, string $name = "givefield", string $description = "Give field to other Player", string $usageMessage = "/givefield [player] [id]", $aliases = ['Player', 'Id']) {
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
            $a = (!isset($args[1])) ? $this->owner->fieldloader->getAreaByVector3($sender) : $this->owner->fieldloader->getAreaById($args[1]);
            if ($a == \null) {
                $sender->sendMessage("Not Registered");
                return \true;
            }
            if (!isset($args[0])) {
                $sender->sendMessage($this->getUsage());
                return \true;
            }
            $pl = Server::getInstance()->getPlayer($args[0]);
            if ($pl == \null) {
                $sender->sendMessage("Doesn't exist");
                return \true;
            }
            if (count($this->owner->fieldloader->getAreasByOwner($pl->getName())) >= FieldLoader::Maximum_Lands) {
                $sender->sendMessage("He has maximum Lands");
                return \true;
            }
            $a->setOwner($pl);
            $sender->sendMessage("Owner Changed!!");
            $pl->sendMessage("You got {$a->getLandnum()} by {$sender->getName()}");
        }
    }