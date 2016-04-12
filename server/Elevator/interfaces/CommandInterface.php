<?php
interface CommandInterface {

    public function executed();

    public function getStatus();

    public function getFloor();

    public function getDirection();

}
