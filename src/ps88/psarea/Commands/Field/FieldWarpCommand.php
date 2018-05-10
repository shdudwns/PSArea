<?php
    namespace ps88\psarea\Commands\Field;

    use nlog\StormCore\StormPlayer;
    use pocketmine\command\Command;
    use pocketmine\command\CommandSender;
    use pocketmine\level\Position;
    use pocketmine\Player;
    use pocketmine\Server;
    use ps88\psarea\Loaders\Field\FieldLoader;
    use ps88\psarea\PSAreaMain;

    class FieldWarpCommand extends Command {

        /** @var PSAreaMain */
        private $owner;

        /**
         * FieldInfoCommand constructor.
         * @param string $name
         * @param PSAreaMain $owner
         * @param string $description
         * @param string|null $usageMessage
         * @param array $aliases
         */
        public function __construct(PSAreaMain $owner, string $name = "warpfield", string $description = "Warp to field", string $usageMessage = "/warpfield [id]", $aliases = ['Id']) {
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
            if (!isset($args[0])) {
                $sender->sendMessage($this->getUsage());
                return \true;
            }
            $id = (int) $args[0];
            if (($a = $this->owner->fieldloader->getAreaById($id)) == \null) {
                $sender->sendMessage(PSAreaMain::get("not-registered"));
                return \true;
            }
            if (!$a->Warp($sender)) {
                $sender->sendMessage(PSAreaMain::get("cancelled"));
                return \true;
            }
            $sender->sendMessage(PSAreaMain::get("warp-to", \true, ["@landnum", $id], ["@type", "field"]));
            return \true;
        }
    }