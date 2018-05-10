<?php
    namespace ps88\psarea\Commands\Land;

    use nlog\StormCore\StormPlayer;
    use pocketmine\command\Command;
    use pocketmine\command\CommandSender;
    use pocketmine\Player;
    use ps88\psarea\Loaders\Land\LandLoader;
    use ps88\psarea\PSAreaMain;

    class LandInfoCommand extends Command {

        /** @var PSAreaMain */
        private $owner;

        /**
         * LandInfoCommand constructor.
         * @param string $name
         * @param PSAreaMain $owner
         * @param string $description
         * @param string|null $usageMessage
         * @param array $aliases
         */
        public function __construct(PSAreaMain $owner, string $name = "infoland", string $description = "Fet Land info", string $usageMessage = "/infoland [id]", $aliases = ['Id']) {
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
            $a = (!isset($args[0])) ? $this->owner->landloader->getAreaByPosition($sender) : $this->owner->landloader->getAreaById($args[0]);
            if ($a == \null) {
                $sender->sendMessage(PSAreaMain::get("not-registered"));
                return \true;
            }
            $sender->sendMessage(PSAreaMain::get("info-start", \true, ["@landnum", $a->getLandnum()], ["@type", "land"]));
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