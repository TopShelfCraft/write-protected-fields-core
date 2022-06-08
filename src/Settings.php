<?php
namespace TopShelfCraft\WriteProtectedFields;

use craft\base\Field;

class Settings
{

	/**
	 * @var bool
	 */
	public $default = false;

	/**
	 * @var bool|callable|array|null
	 */
	public $writeProtected = null;

	public function shouldRenderAsWriteProtected(Field $field): bool
	{

		if (is_bool($this->writeProtected))
		{
			return $this->writeProtected;
		}

		if (is_callable($this->writeProtected))
		{
			return ($this->writeProtected)($field);
		}

		if (is_array($this->writeProtected) && isset($this->writeProtected[$field->handle]))
		{
			if (is_callable($this->writeProtected[$field->handle]))
			{
				return ($this->writeProtected[$field->handle])($field);
			}
			return (bool)$this->writeProtected[$field->handle];
		}

		return $this->default;

	}

}
