<?php

class AdminAccount extends CWidget
{

	public function init() {
	}

	public function run() {
		$this->renderContent();
	}

	protected function renderContent() 
	{
		$this->render('admin_account');	
	}
}
