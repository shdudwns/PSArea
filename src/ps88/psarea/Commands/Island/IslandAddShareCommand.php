<?php
    namespace ps88\psarea\Commands\Island;

    use nlog\StormCore\StormPlayer;
    use pocketmine\command\Command;
    use pocketmine\command\CommandSender;
    use pocketmine\Server;
    use ps88\psarea\Loaders\Island\IslandLoader;
    use ps88\psarea\PSAreaMain;

    class IslandAddShareCommand extends Command{

        /** @var PSAreaMain */
        private $owner;

        /**
         * IslandAddShareCommand constructor.
         * @param string $name
         * @param PSAreaMain $owner
         * @param string $description
         * @param string|null $usageMessage
         * @param array $aliases
         */
        public function __construct(PSAreaMain $owner, string $name = "addislandshare", string $description = "Add Island Shared Player", string $usageMessage = "/addislandshare [player] [id]", $aliases = ['Player', 'Id']) {
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
            $id = (! isset($args[1]))? $this->owner->islandloader->getAreaByVector3($sender) : $args[1];
            if(($a = $this->owner->islandloader->getAreaById($id)) == \null){
                $sender->sendMessage("Not Registered");
                return;
            }
            if($a->owner->getName() !== $sender->getName()){
                $sender->sendMessage("It's not your island");
                return;
            }
            if(! isset($args[1])){
                $sender->sendMessage($this->getUsage());
                return;
            }
            $pl = Server::getInstance()->getPlayer($args[0]);
            if($pl == \null){
                $sender->sendMessage("Doesn't exist");
                return;
            }
            $a->addShare($pl);
            $sender->sendMessage("You add {$pl->getName()} at {$id} island");
            return;
        }
    }