<?php
    namespace ps88\psarea\ProtectWorld;

    use pocketmine\event\block\BlockBreakEvent;
    use pocketmine\event\block\BlockPlaceEvent;
    use pocketmine\event\Listener;
    use ps88\psarea\PSAreaMain;

    class ProtectWorldListener implements Listener {

        /** @var PSAreaMain */
        public $main;

        public function __construct(PSAreaMain $main) {
            $this->main = $main;
        }

        public function BlockBreak(BlockBreakEvent $ev) {
            $pl = $ev->getPlayer();
            if ($pl->isOp()) return;
            if ((($a = $this->main->islandloader->getAreaByVector3($ev->getBlock()->asVector3())) !== null and $pl->level->getName() == 'island') or (($a = $this->main->skylandloader->getAreaByVector3($ev->getBlock()->asVector3())) !== null and $pl->level->getName() == 'skyland') or (($a = $this->main->fieldloader->getAreaByVector3($ev->getBlock()->asVector3())) !== null and $pl->level->getName() == 'field')) {
                if ($a->owner == \null) {
                    if ($a->getShare($pl->getName()) == \null) {
                        $ev->setCancelled();
                        $pl->sendMessage("You don't have permission to do it");
                    }
                } elseif ($a->owner->getName() !== $pl->getName()) {
                    if ($a->getShare($pl->getName()) == \null) {
                        $ev->setCancelled();
                        $pl->sendMessage("You don't have permission to do it");
                    }
                } elseif ($a->owner->getName() == $pl->getName()) {
                    return;
                }
            }
            if (!ProtectWorld::getInstance()->isLevelProtected($pl->getLevel())) {
                if (($a = $this->main->landloader->getAreaByPosition($pl->asPosition())) !== null) {
                    if ($a->owner == \null) {
                        $ev->setCancelled();
                        return;
                    }
                    if ($a->owner->getName() == $pl->getName() or $a->getShare($pl->getName()) !== \null) {
                        return;
                    }
                }
                $ev->setCancelled();
                return;
            } else {
                $ev->setCancelled();
            }
            return;
        }

        public function BlockPlace(BlockPlaceEvent $ev) {
            $pl = $ev->getPlayer();
            if ($pl->isOp()) return;
            if ((($a = $this->main->islandloader->getAreaByVector3($ev->getBlock()->asVector3())) !== null and $pl->level->getName() == 'island') or (($a = $this->main->skylandloader->getAreaByVector3($ev->getBlock()->asVector3())) !== null and $pl->level->getName() == 'skyland') or (($a = $this->main->fieldloader->getAreaByVector3($ev->getBlock()->asVector3())) !== null and $pl->level->getName() == 'field')) {
                if ($a->owner == \null) {
                    if ($a->getShare($pl->getName()) == \null) {
                        $ev->setCancelled();
                        $pl->sendMessage("You don't have permission to do it");
                    }
                } elseif ($a->owner->getName() !== $pl->getName()) {
                    if ($a->getShare($pl->getName()) == \null) {
                        $ev->setCancelled();
                        $pl->sendMessage("You don't have permission to do it");
                    }
                } elseif ($a->owner->getName() == $pl->getName()) {
                    return;
                }
            }
            if (!ProtectWorld::getInstance()->isLevelProtected($pl->getLevel())) {
                if (($a = $this->main->landloader->getAreaByPosition($pl->asPosition())) !== null) {
                    if ($a->owner == \null) {
                        $ev->setCancelled();
                        return;
                    }
                    if ($a->owner->getName() == $pl->getName() or $a->getShare($pl->getName()) !== \null) {
                        return;
                    }
                }
                $ev->setCancelled();
                return;
            } else {
                $ev->setCancelled();
            }
            return;
        }
    }