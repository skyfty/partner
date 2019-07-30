<?php 

class AppBehavior extends Behavior {
	protected $options = array();
	public function run(&$params) {
        C('defaultinfo', F('defaultinfo'.session('league_id')));
	}
}