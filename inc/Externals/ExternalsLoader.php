<?php

namespace BrandyBlocks\Externals;

use BrandyBlocks\Traits\SingletonTrait;

class ExternalsLoader {
	use SingletonTrait;

	protected function __construct() {
		require_once __DIR__ . '/Settings/StyleProductOriginalPrice/Caller.php';
		require_once __DIR__ . '/Settings/ResponsiveConditions/Caller.php';
		require_once __DIR__ . '/Settings/DisplayAnimation/Caller.php';
		require_once __DIR__ . '/Settings/VisibilityConditions/Caller.php';
		require_once __DIR__ . '/Settings/ButtonSettings/Caller.php';
	}
}
