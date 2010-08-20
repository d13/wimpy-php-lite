<?php

interface DataSource {
	public function query ();
	public function close ();
}