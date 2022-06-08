<?php
namespace TopShelfCraft\WriteProtectedFields;

use Craft;
use craft\base\Field;
use craft\events\DefineFieldHtmlEvent;
use yii\base\Behavior;

/**
 * @property Field $owner
 */
class FieldBehavior extends Behavior
{

	/**
	 * @var bool
	 */
	public $inDefineInputHtmlEvent = false;

	public function events(): array
	{
		return [
			Field::EVENT_DEFINE_INPUT_HTML => [$this, 'handleDefineInputHtml']
		];
	}

	/**
	 * Renders fields as static where appropriate.
	 */
	public function handleDefineInputHtml(DefineFieldHtmlEvent $event)
	{

		// Don't mess with fields if current user is Admin.
		if (Craft::$app->getUser()->getIsAdmin())
		{
			return;
		}

		// Skip to avoid infinite recursion, if we're already in this event.
		if ($this->inDefineInputHtmlEvent)
		{
			return;
		}
		$this->inDefineInputHtmlEvent = true;

		/** @var Field $field */
		$field = $event->sender;

		if (WriteProtectedFields::getInstance()->getSettings()->shouldRenderAsWriteProtected($field))
		{
			 $event->html = $field->getStaticHtml($event->value, $event->element);
		}

		$this->inDefineInputHtmlEvent = false;

	}

}
