<?php
    namespace ps88\psarea\Loaders\base;

    use pocketmine\level\Position;
    use pocketmine\math\Vector2;
    use pocketmine\Player;
    use pocketmine\Server;
    use ps88\psarea\Events\LandAddShareEvent;
    use ps88\psarea\Events\LandWarpEvent;

    abstract class BaseArea {
        public const Island = 0;
        public const Field = 1;
        public const Skyland = 2;

        /** @var int */
        public const LandType = -1;

        /** @var Vector2 */
        public $minvec;

        /** @var Vector2 */
        public $maxvec;

        /** @var Player|null */
        public $owner = \null;

        /** @var Player[] */
        public $shares = [];

        /** @var int */
        private $landnum;

        /**
         * BaseArea constructor.
         * @param int $landnum
         * @param Vector2 $minvec
         * @param Vector2 $maxvec
         * @param Player|null $owner
         * @param Player[] $shares
         */
        public function __construct(int $landnum, Vector2 $minvec, Vector2 $maxvec, ?Player $owner = \null, array $shares = []) {
            $this->landnum = $landnum;
            $this->minvec = $minvec;
            $this->maxvec = $maxvec;
            $this->owner = $owner;
            $this->shares = $shares;
        }

        /**
         * @return Vector2
         */
        public function getMinVector(): Vector2 {
            return $this->minvec;
        }

        /**
         * @param Vector2 $minvec
         */
        public function setMinVector(Vector2 $minvec): void {
            $this->minvec = $minvec;
        }

        /**
         * @return Vector2
         */
        public function getMaxVector(): Vector2 {
            return $this->maxvec;
        }

        /**
         * @param Vector2 $maxvec
         */
        public function setMaxVector(Vector2 $maxvec): void {
            $this->maxvec = $maxvec;
        }

        /**
         * @return null|Player
         */
        public function getOwner(): ?Player {
            return $this->owner;
        }

        /**
         * @param null|Player $owner
         */
        public function setOwner(?Player $owner): void {
            $this->owner = $owner;
        }

        /**
         * @return Player[]
         */
        public function getShares(): array {
            return $this->shares;
        }

        public function getShare(string $name): ?Player {
            foreach ($this->getShares() as $player) {
                if ($player->getName() == $name) return $player;
            }
            return \null;
        }

        public function addShare(Player $pl): void {
            if ($this->getShare($pl->getName()) == \null) return;
            Server::getInstance()->getPluginManager()->callEvent($ev = new LandAddShareEvent($this, $pl));
            if($ev->isCancelled()) return;
            array_push($this->shares, $pl);
        }

        /**
         * @return int
         */
        public function getLandnum(): int {
            return $this->landnum;
        }

        public function Warp(Player $pl): bool{
            $v = $this->getMinVector();
            $v2 = $this->getMaxVector();
            Server::getInstance()->getPluginManager()->callEvent($ev = new LandWarpEvent($this, $pl));
            if($ev->isCancelled()) return \false;
            $pl->teleport(new Position(($v->x + $v2->x) / 2, 14, ($v->y + $v2->y) / 2, Server::getInstance()->getLevelByName('island')));
            return \true;
        }
    }