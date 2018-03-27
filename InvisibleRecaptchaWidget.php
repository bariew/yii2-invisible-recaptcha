<?php
/**
 * InvisibleRecaptchaWidget class file.
 * @copyright (c) 2018, Bariev Pavel
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace bariew\invisibleRecaptcha;

use yii\helpers\Html;
use yii\widgets\InputWidget;


/**
 *
 * @author Pavel Bariev <bariew@yandex.ru>
 */
class InvisibleRecaptchaWidget extends InputWidget
{
    /**
     * @var string
     */
    public $buttonText = 'Submit';

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->getView()->registerJsFile('https://www.google.com/recaptcha/api.js');
        $this->field->template = "{input}\n{error}";
        $callbackRandomString = time();
        $formId = $this->field->form->id;
        $inputId = Html::getInputId($this->model, $this->attribute);
        $recaptchaId = InvisibleRecaptchaValidator::POST_PARAM;
        $this->getView()->registerJs(<<<JS
            var recaptchaCallback_{$callbackRandomString} = function() {
                $('#{$inputId}').val($('#{$recaptchaId}').val());
                $('#{$formId}').submit();
            }
JS
        );
        $options = array_merge([
            'data-sitekey'  => InvisibleRecaptchaValidator::key(),
            'data-callback' => 'recaptchaCallback_' . $callbackRandomString
        ], $this->options);
        Html::addCssClass($options, 'g-recaptcha recaptcha');
        return
            Html::activeHiddenInput($this->model, $this->attribute)
            . Html::button($this->buttonText, $options);
    }
}