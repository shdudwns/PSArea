<?php
    namespace ps88\psarea\MoneyTranslate;

    use nlog\StormCore\StormCore;
    use nlog\StormCore\StormPlayer;
    use onebone\economyapi\EconomyAPI;
    use pocketmine\Player;
    use pocketmine\plugin\Plugin;
    use pocketmine\Server;

    class MoneyTranslator {
        /** @var Plugin */
        private $moneyAPI;

        /** @var MoneyTranslator|null */
        private static $instance = null;

        public function __construct(Plugin $moneyAPI) {
            self::$instance = $this;
            $this->moneyAPI = $moneyAPI;
        }

        public function getMoney($player): ?int {
            $pl = ($player instanceof Player) ? $player : Server::getInstance()->getPlayer($player);
            if ($pl == \null and $pl = Server::getInstance()->getOfflinePlayer($player) == \null) return \null;
            if ($this->moneyAPI instanceof EconomyAPI) {
                return $this->moneyAPI->myMoney($player);
            } elseif ($this->moneyAPI instanceof StormCore && $pl instanceof StormPlayer) {
                return $pl->getMoney();
            }
            return \null;
        }

        public function addMoney($player, int $money): bool {
            $pl = ($player instanceof Player) ? $player : Server::getInstance()->getPlayer($player);
            if ($pl == \null and $pl = Server::getInstance()->getOfflinePlayer($player) == \null) return \false;
            if ($this->moneyAPI instanceof EconomyAPI) {
                $this->moneyAPI->addMoney($player, $money);
                return true;
            } elseif ($this->moneyAPI instanceof StormCore && $pl instanceof StormPlayer) {
                $pl->addMoney($money);
                return true;
            }
            return false;
        }

        public function reduceMoney($player, int $money): bool {
            $pl = ($player instanceof Player) ? $player : Server::getInstance()->getPlayer($player);
            if ($pl == \null and $pl = Server::getInstance()->getOfflinePlayer($player) == \null) return \false;
            if ($this->moneyAPI instanceof EconomyAPI) {
                $this->moneyAPI->reduceMoney($player, $money);
                return true;
            } elseif ($this->moneyAPI instanceof StormCore && $pl instanceof StormPlayer) {
                $pl->reduceMoney($money);
                return true;
            }
            return false;
        }

        public function setMoney($player, int $money): bool {
            $pl = ($player instanceof Player) ? $player : Server::getInstance()->getPlayer($player);
            if ($pl == \null and $pl = Server::getInstance()->getOfflinePlayer($player) == \null) return \false;
            if ($this->moneyAPI instanceof EconomyAPI) {
                $this->moneyAPI->setMoney($player, $money);
                return true;
            } elseif ($this->moneyAPI instanceof StormCore && $pl instanceof StormPlayer) {
                $pl->setMoney($money);
                return true;
            }
            return false;
        }

        public static function getInstance(): ?MoneyTranslator {
            return self::$instance;
        }
    }
