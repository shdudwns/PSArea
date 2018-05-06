<?php
    namespace mpm\Generator;

    /*
     * 이 코드는 SOLOLand(Nukkit) 에서 가져왔으며
     * PS88이 java에서 php로 번역하였음을 알려드립니다->
     */

    use pocketmine\level\generator\Generator;
    use pocketmine\block\Block;
    use pocketmine\block\Stone as BlockStone;
    use pocketmine\level\ChunkManager;
    use pocketmine\math\Vector2;
    use pocketmine\math\Vector3;
    use pocketmine\utils\{
            Random, Config
    };

    class FieldGenerator extends Generator {

        /** @var ChunkManager */
        private $level;

        /** @var array */
        private $options;

        /** @var int */
        private $floorLevel;

        /** @var string */
        private $preset = "1;7,4x1,3x3;3;road(block=1:6 width=5 depth=5),land(width=32 depth=32 border=43 block=2)";

        /** @var int */
        private $version = 1;

        /** @var int[] */
        private $flatBlocksId = [Block::BEDROCK, Block::STONE, Block::STONE, Block::STONE, Block::STONE, Block::DIRT, Block::DIRT, Block::DIRT];
        /** @var int[] */
        private $flatBlocksDamage = [0, 0, 0, 0, 0, 0, 0, 0];
        /** @var int */
        private $roadBlockId = Block::STONE;
        /** @var int */
        private $roadBlockDamage = BlockStone::POLISHED_ANDESITE;
        /** @var int */
        private $roadWidth = 5;
        /** @var int */
        private $roadDepth = 5;

        /** @var int */
        private $landBlockId = 2;
        /** @var int */
        private $landBlockDamage = 0;
        /** @var int */
        private $landWidth = 32;
        /** @var int */
        private $landDepth = 32;
        /** @var int */
        private $landBorderBlockId = Block::DOUBLE_STONE_SLAB;
        /** @var int */
        private $landBorderBlockDamage = 0;


        /**
         * @return ChunkManager
         */
        public function getChunkManager(): ChunkManager {
            return $this->level;
        }


        /**
         * @return array
         */
        public function getSettings(): array {
            return $this->options;
        }


        public function getName(): string {
            return "field";
        }

        public function __construct(array $settings = []) {
            parent::__construct($settings);
        }

        public function getLandWidth(): int {
            return $this->landWidth;
        }

        public function getLandDepth(): int {
            return $this->landDepth;
        }

        public function getRoadWidth(): int {
            return $this->roadWidth;
        }

        public function getRoadDepth(): int {
            return $this->roadDepth;
        }


        /**
         * @param ChunkManager $level
         * @param Random? $random
         */
        public function init(ChunkManager $level, Random $random = \null) {
            $this->level = $level;
        }


        /**
         * @param int $chunkX
         * @param int $chunkZ
         */
        public function generateChunk(int $chunkX, int $chunkZ): void {
            $chunk = $this->level->getChunk($chunkX, $chunkZ);
            if ($chunkX >= 0 && $chunkZ >= 0) {
                for ($x = 0; $x <= 15; $x++) {
                    for ($z = 0; $z <= 15; $z++) {
                        for ($i = 0; $i < count($this->flatBlocksId); $i++) {
                            $chunk->setBlock($x, $i, $z, $this->flatBlocksId[$i], $this->flatBlocksDamage[$i]);
                        }
                        $calcRet = $this->calcGen($chunkX * 16 + $x, $chunkZ * 16 + $z);
                        $chunk->setBlock($x, count($this->flatBlocksId), $z, $calcRet[0], $calcRet[1]);
                    }
                }
            }
            $this->level->setChunk($chunkX, $chunkZ, $chunk);
        }

        /**
         * @param int $worldX
         * @param int $worldZ
         * @return array
         */
        private function calcGen(int $worldX, int $worldZ): array {
            $landBlock = [$this->landBlockId, $this->landBlockDamage];
            $roadBlock = [$this->roadBlockId, $this->roadBlockDamage];
            $landBorder = [$this->landBorderBlockId, $this->landBorderBlockDamage];

            if ($worldX == 0 || $worldZ == 0) {
                return $landBorder;
            }
            $gridlandx = $worldX % ($this->landWidth + $this->roadWidth);
            $gridlandz = $worldZ % ($this->landDepth + $this->roadDepth);


            if ($gridlandx >= ($this->roadWidth + 2) && $gridlandz >= ($this->roadDepth + 2)) {
                return $landBlock;
            }

            //center grass edge (block code 43) part
            if ($gridlandx >= ($this->roadWidth + 1) && $gridlandz >= ($this->roadDepth + 1)) {
                return $landBorder;
            }

            //road (block code 1:6) part
            if ($gridlandx >= 1 && $gridlandz >= 1) {
                return $roadBlock;
            }

            if ($gridlandx == 0 && $gridlandz >= ($this->roadDepth + 1)) {
                return $landBorder;
            }
            if ($gridlandz == 0 && $gridlandx >= ($this->roadWidth + 1)) {
                return $landBorder;
            }
            if ($gridlandx == 0 && $gridlandz == 0) {
                return $landBorder;
            }
            return $roadBlock;
        }


        public function populateChunk(int $chunkX, int $chunkZ): void {
        }


        public function getSpawn(): Vector3 {
            return new Vector3(128, $this->floorLevel, 128);
        }

    }

?>
