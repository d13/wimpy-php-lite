<?php
interface ViewBuilder {
	public function getFinalView ($tplName,$modelMap);
}