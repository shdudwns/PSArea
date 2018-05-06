<?php
    namespace ps88\psarea\MoneyTranslate;

    use nlog\StormCore\StormCore;
    use nlog\StormCore\StormPlayer;
    use onebone\economyapi\EconomyAPI;
    use pocketmine\Player;
    use pocketmine\plugin\Plugin;
    use pocketmine\Server;

    class MoneyTranslator{
        /** @var Plugin  */
        private $moneyAPI;

        /** @var MoneyTranslator  */
        private static $instance;

        public function __construct(Plugin $moneyAPI) {
            self::$instance = $this;
            $this->moneyAPI = $moneyAPI;
        }

        public function getMoney($player): ?int{
            $pl = ($player instanceof Player)? $player : Server::getInstance()->getPlayer($player);
            if($this->moneyAPI instanceof EconomyAPI){
                return $this->moneyAPI->myMoney($player);
            }elseif($this->moneyAPI instanceof StormCore){
                if($pl instanceof StormPlayer){
                    return $pl->getMoney();
                }
            }
            return \null;
        }

        public function addMoney($player, int $money): void{
            $pl = ($player instanceof Player)? $player : Server::getInstance()->getPlayer($player);
            if($this->moneyAPI instanceof EconomyAPI){
                $this->moneyAPI->addMoney($player, $money);
            }elseif($this->moneyAPI instanceof StormCore){
                if($pl instanceof StormPlayer){
                    $pl->addMoney($money);
                }
            }
        }

        public function reduceMoney($player, int $money): void{
            $pl = ($player instanceof Player)? $player : Server::getInstance()->getPlayer($player);
            if($this->moneyAPI instanceof EconomyAPI){
                $this->moneyAPI->reduceMoney($player, $money);
            }elseif($this->moneyAPI instanceof StormCore){
                if($pl instanceof StormPlayer){
                    $pl->reduceMoney($money);
                }
            }
        }

        public function setMoney($player, int $money): void{
            $pl = ($player instanceof Player)? $player : Server::getInstance()->getPlayer($player);
            if($this->moneyAPI instanceof EconomyAPI){
                $this->moneyAPI->setMoney($player, $money);
            }elseif($this->moneyAPI instanceof StormCore){
                if($pl instanceof StormPlayer){
                    $pl->setMoney($money);
                }
            }
        }

        public static function getInstance(): self{
            return self::$instance;
        }
    }