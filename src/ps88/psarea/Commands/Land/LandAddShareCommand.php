<?php
    namespace ps88\psarea\Commands\Land;

    use nlog\StormCore\StormPlayer;
    use pocketmine\command\Command;
    use pocketmine\command\CommandSender;
    use pocketmine\Player;
    use pocketmine\Server;
    use ps88\psarea\Loaders\Land\LandLoader;
    use ps88\psarea\PSAreaMain;

    class LandAddShareCommand extends Command {

        /** @var PSAreaMain */
        private $owner;

        /**
         * LandAddShareCommand constructor.
         * @param string $name
         * @param PSAreaMain $owner
         * @param string $description
         * @param string|null $usageMessage
         * @param array $aliases
         */
        public function __construct(PSAreaMain $owner, string $name = "addlandshare", string $description = "Add Land Shared Player", string $usageMessage = "/addlandshare [player] [id]", $aliases = ['Player', 'Id']) {
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
                $sender->sendMessage(PSAreaMain::get("only-player"));
                return \true;
            }
            $a = (!isset($args[1])) ? $this->owner->landloader->getAreaByPosition($sender) : $this->owner->landloader->getAreaById($args[1]);
            if ($a == \null) {
                $sender->sendMessage(PSAreaMain::get("not-registered"));
                return \true;
            }
            if ($a->owner == \null) {
                $sender->sendMessage(PSAreaMain::get("not-yours", \true, ["@type", "land"]));
                return \true;
            }
            if ($a->owner->getName() !== $sender->getName()) {
                $sender->sendMessage(PSAreaMain::get("not-yours", \true, ["@type", "land"]));
                return \true;
            }
            if (!isset($args[1])) {
                $sender->sendMessage($this->getUsage());
                return \true;
            }
            $pl = Server::getInstance()->getPlayer($args[0]);
            if ($pl == \null) {
                $sender->sendMessage(PSAreaMain::get("doesnt-exist"));
                return \true;
            }
            $a->addShare($pl);
            $sender->sendMessage(PSAreaMain::get("you-add-share", \true, ["@player", $pl->getName()], ["@landnum", $a->getLandnum()], ["@type", "land"]));
            return \true;
        }
    }