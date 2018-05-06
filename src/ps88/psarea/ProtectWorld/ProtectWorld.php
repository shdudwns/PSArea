<?php
    namespace ps88\psarea\ProtectWorld;

    use pocketmine\level\Level;
    use pocketmine\Server;
    use ps88\psarea\PSAreaMain;

    class ProtectWorld {
        /** @var ProtectWorld */
        private static $instance;

        /** @var array */
        public $levels = [];

        /** @var PSAreaMain */
        public $main;

        public function __construct(PSAreaMain $main) {
            Server::getInstance()->getPluginManager()->registerEvents(new ProtectWorldListener($main), $main);
            $this->main = $main;
            self::$instance = $this;
        }

        public function isLevelProtected(Level $level): bool {
            if (!isset($this->levels[$level->getId()])) return \false;
            return $this->levels[$level->getId()];
        }

        public function setLevelProtect(Level $level, bool $isProtected): void {
            $this->levels[$level->getId()] = $isProtected;
        }

        public static function getInstance(): self {
            return self::$instance;
        }
    }