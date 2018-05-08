<?php
    namespace ps88\psarea\Loaders\Land;

    use pocketmine\event\Listener;
    use pocketmine\event\player\PlayerInteractEvent;
    use pocketmine\math\Vector2;
    use ps88\psarea\PSAreaMain;

    class LandListener implements Listener {

        /** @var PSAreaMain */
        public $main;

        public function __construct(PSAreaMain $main) {
            $this->main = $main;
        }

        public function get(PlayerInteractEvent $ev) {
            $pl = $ev->getPlayer();
            $bl = $ev->getTouchVector();
            if (!$this->main->landloader->DoingRegister($pl)) return;
            if (!$this->main->landloader->isFirstVecRegister($pl)) {
                $this->main->landloader->FirstVecRegister($pl, new Vector2($bl->x, $bl->z));
                $pl->sendMessage("Registered First Vector2");
            } elseif (!$this->main->landloader->isSecondVecRegister($pl)) {
                $this->main->landloader->SecondVecRegister($pl, new Vector2($bl->x, $bl->z));
                $pl->sendMessage("Registered all");
                $v1 = $this->main->landloader->getRegisters($pl)[2];
                $v2 = $this->main->landloader->getRegisters($pl)[3];
                if ($v1->x > $v2->x) {
                    $mnx = $v2->x;
                    $mxx = $v1->x;
                } else {
                    $mnx = $v1->x;
                    $mxx = $v2->x;
                }
                if ($v1->y > $v2->y) {
                    $mny = $v2->y;
                    $mxy = $v1->y;
                } else {
                    $mny = $v1->y;
                    $mxy = $v2->y;
                }
                $mnv = new Vector2($mnx, $mny);
                $mxv = new Vector2($mxx, $mxy);
                $this->main->landloader->addArea(new LandArea($this->main->landloader->registeringNum($pl), $this->main->landloader->registeringLevel($pl), $mnv, $mxv));
            }
        }
    }