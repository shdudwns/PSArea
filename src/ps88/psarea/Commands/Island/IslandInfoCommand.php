<?php
    namespace ps88\psarea\Commands\Island;

    use nlog\StormCore\StormPlayer;
    use pocketmine\command\Command;
    use pocketmine\command\CommandSender;
    use ps88\psarea\Loaders\Island\IslandLoader;
    use ps88\psarea\PSAreaMain;

    class IslandInfoCommand extends Command{

        /** @var PSAreaMain */
        private $owner;

        /**
         * IslandInfoCommand constructor.
         * @param string $name
         * @param PSAreaMain $owner
         * @param string $description
         * @param string|null $usageMessage
         * @param array $aliases
         */
        public function __construct(PSAreaMain $owner, string $name = "infoisland", string $description = "Fet Island info", string $usageMessage = "/infoisland [id]", $aliases = ['Id']) {
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
            if(! $sender instanceof StormPlayer){
                $sender->sendMessage("Only Player Can see this.");
                return;
            }
            $id = (! isset($args[0]))? $this->owner->islandloader->getAreaByVector3($sender) : $args[0];
            if(($a = $this->owner->islandloader->getAreaById($id)) == \null){
                $sender->sendMessage("Not Registered");
                return;
            }
            $sender->sendMessage("====[{$id} island]====");
            $owner = ($a->owner == \null)? "None" : $a->owner->getName();
            $sender->sendMessage("Owner : {$owner}");
            $sender->sendMessage("Shares :");
            if(empty($a->getShares())) {
                $sender->sendMessage("None");
            }else {
                foreach ($a->getShares() as $share) {
                    $sender->sendMessage($share->getName());
                }
            }
            return;
        }
    }