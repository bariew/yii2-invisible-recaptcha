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
 * @see README.md
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
        $this->getView()->registerJsFile('https://www.google.com/recaptcha/api.js', [
            'async' => 'async',
            'defer' => 'defer'
        ]);
        $this->field->template = "{input}\n{error}";
        $callbackRandomString = time();
        $formId = $this->field->form->id;
        $inputId = Html::getInputId($this->model, $this->attribute);
        $recaptchaId = InvisibleRecaptchaValidator::POST_PARAM;
        $options = array_merge([
            'data-sitekey'  => InvisibleRecaptchaValidator::key(),
            'data-callback' => "recaptchaCallback_{$callbackRandomString}"
        ], $this->options, ['id' => 'recaptchaButton'.$callbackRandomString]);
        Html::addCssClass($options, 'g-recaptcha recaptcha');
        return Html::activeHiddenInput($this->model, $this->attribute)
            . Html::button($this->buttonText, $options)
            . Html::tag('script', <<<JS
var recaptchaCallback_{$callbackRandomString} = function() {
    $('#{$inputId}').val($('#{$recaptchaId}').val());
    $('#{$formId}').submit();
}
grecaptcha.render('recaptchaButton{$callbackRandomString}'); // this helps to ajax form refresh
JS
            );
    }
}

