<?php
    namespace ps88\psarea\Commands\Field;

    use pocketmine\command\Command;
    use pocketmine\command\CommandSender;
    use ps88\psarea\PSAreaMain;

    class FieldRegisteredList extends Command{
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
        public function __construct(PSAreaMain $owner, string $name = "fieldlist", string $description = "field list", string $usageMessage = "/fieldlist", $aliases = []) {
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
            $s = "[Field List] : ";
            foreach ($this->owner->fieldloader->getAreas() as $area) {
                if($area->owner == \null) continue;
                $s .= "[" . $area->getLandnum() . "]";
            }
            $sender->sendMessage($s);
            return \true;
        }
    }