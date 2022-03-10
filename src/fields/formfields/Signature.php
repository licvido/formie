<?php
namespace verbb\formie\fields\formfields;

use verbb\formie\base\FormField;
use verbb\formie\helpers\SchemaHelper;

use Craft;
use craft\base\ElementInterface;
use craft\base\PreviewableFieldInterface;
use craft\db\mysql\Schema;
use craft\helpers\Html;
use craft\helpers\Template;

class Signature extends FormField implements PreviewableFieldInterface
{
    // Static Methods
    // =========================================================================

    /**
     * @inheritDoc
     */
    public static function displayName(): string
    {
        return Craft::t('formie', 'Signature');
    }

    /**
     * @inheritDoc
     */
    public static function getSvgIconPath(): string
    {
        return 'formie/_formfields/signature/icon.svg';
    }


    // Properties
    // =========================================================================

    public string $backgroundColor = 'transparent';
    public string $penColor = '#000000';
    public string $penWeight = '2';


    // Public Methods
    // =========================================================================

    /**
     * @inheritDoc
     */
    public function getContentColumnType(): array|string
    {
        return Schema::TYPE_MEDIUMTEXT;
    }

    /**
     * @inheritDoc
     */
    public function getInputHtml(mixed $value, ?ElementInterface $element = null): string
    {
        return Html::tag('img', null, ['src' => $value]);
    }

    /**
     * @inheritDoc
     */
    public function getPreviewInputHtml(): string
    {
        return Craft::$app->getView()->renderTemplate('formie/_formfields/signature/preview', [
            'field' => $this
        ]);
    }

    public function getFrontEndJsModules(): ?array
    {
        return [
            'src' => Craft::$app->getAssetManager()->getPublishedUrl('@verbb/formie/web/assets/frontend/dist/js/fields/signature.js', true),
            'module' => 'FormieSignature',
            'settings' => [
                'backgroundColor' => $this->backgroundColor,
                'penColor' => $this->penColor,
                'penWeight' => $this->penWeight,
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getFieldDefaults(): array
    {
        return [
            'backgroundColor' => 'transparent',
            'penColor' => '#000000',
            'penWeight' => '2',
        ];
    }

    /**
     * @inheritDoc
     */
    public function defineGeneralSchema(): array
    {
        return [
            SchemaHelper::labelField(),
            SchemaHelper::textField([
                'label' => Craft::t('formie', 'Background Color'),
                'help' => Craft::t('formie', 'Set the background color.'),
                'name' => 'backgroundColor',
                'size' => '4',
                'type' => 'color',
                'class' => 'text fui-color-field',
            ]),
            SchemaHelper::textField([
                'label' => Craft::t('formie', 'Pen Color'),
                'help' => Craft::t('formie', 'Set the pen color.'),
                'name' => 'penColor',
                'size' => '4',
                'type' => 'color',
                'class' => 'text fui-color-field',
            ]),
            SchemaHelper::textField([
                'label' => Craft::t('formie', 'Pen Weight'),
                'help' => Craft::t('formie', 'Set the line thickness (weight) for the pen.'),
                'name' => 'penWeight',
            ]),
        ];
    }

    /**
     * @inheritDoc
     */
    public function defineSettingsSchema(): array
    {
        return [
            SchemaHelper::lightswitchField([
                'label' => Craft::t('formie', 'Required Field'),
                'help' => Craft::t('formie', 'Whether this field should be required when filling out the form.'),
                'name' => 'required',
            ]),
            SchemaHelper::toggleContainer('settings.required', [
                SchemaHelper::textField([
                    'label' => Craft::t('formie', 'Error Message'),
                    'help' => Craft::t('formie', 'When validating the form, show this message if an error occurs. Leave empty to retain the default message.'),
                    'name' => 'errorMessage',
                ]),
            ]),
        ];
    }

    /**
     * @inheritDoc
     */
    public function defineAppearanceSchema(): array
    {
        return [
            SchemaHelper::visibility(),
            SchemaHelper::labelPosition($this),
            SchemaHelper::instructions(),
            SchemaHelper::instructionsPosition($this),
        ];
    }

    /**
     * @inheritDoc
     */
    public function defineAdvancedSchema(): array
    {
        return [
            SchemaHelper::handleField(),
            SchemaHelper::cssClasses(),
            SchemaHelper::containerAttributesField(),
        ];
    }

    public function defineConditionsSchema(): array
    {
        return [
            SchemaHelper::enableConditionsField(),
            SchemaHelper::conditionsField(),
        ];
    }


    // Protected Methods
    // =========================================================================

    protected function defineValueForSummary($value, ElementInterface $element = null): string
    {
        return Template::raw(Html::tag('img', null, ['src' => $value]));
    }
}
