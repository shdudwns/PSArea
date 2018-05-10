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
         * @return bool
         */
        public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
            if (!$sender instanceof Player) {
                $sender->sendMessage(PSAreaMain::get("only-player"));
                return \true;
            }
            $a = (!isset($args[0])) ? $this->owner->skylandloader->getAreaByVector3($sender) : $this->owner->skylandloader->getAreaById($args[0]);
            if ($a == \null) {
                $sender->sendMessage(PSAreaMain::get("not-registered"));
                return \true;
            }
            $sender->sendMessage(PSAreaMain::get("info-start", \true, ["@landnum", $a->getLandnum()], ["@type", "skyland"]));
            $owner = ($a->owner == \null) ? PSAreaMain::get("none") : $a->owner->getName();
            $sender->sendMessage(PSAreaMain::get("owner", \true, ["@owner", $owner]));
            $sender->sendMessage(PSAreaMain::get("shares"));
            if (empty($a->getShares())) {
                $sender->sendMessage(PSAreaMain::get("none"));
            } else {
                foreach ($a->getShares() as $share) {
                    $sender->sendMessage($share->getName());
                }
            }
            return \true;
        }
    }