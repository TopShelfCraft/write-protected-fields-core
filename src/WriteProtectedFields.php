<?php
namespace TopShelfCraft\WriteProtectedFields;

use Craft;
use craft\base\Field;
use craft\events\DefineBehaviorsEvent;
use yii\base\Event;
use yii\base\Module;

class WriteProtectedFields extends Module
{

	/**
	 * @var Settings
	 */
	private $_settings;

	public function init()
	{

		Craft::setAlias('@TopShelfCraft/WriteProtectedFields', __DIR__);

		parent::init();
		static::setInstance($this);

		Event::on(
			Field::class,
			Field::EVENT_DEFINE_BEHAVIORS,
			function(DefineBehaviorsEvent $event)
			{
				$event->behaviors['writeProtectedFields'] = FieldBehavior::class;
			}
		);

	}

	public function getSettings(): Settings
	{
		if (!$this->_settings)
		{
			$fileConfig = Craft::$app->config->getConfigFromFile($this->id);
			$this->_settings = Craft::configure(new Settings(), $fileConfig);
		}
		return $this->_settings;
	}

	public static function registerModule(string $id = 'write-protected-fields')
	{
		if (!Craft::$app->getModule($id))
		{
			$module = static::getInstance()
				?? Craft::createObject(static::class, [$id, Craft::$app]);
			Craft::$app->setModule($id, $module);
		}
	}

}
