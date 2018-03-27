
Yii2 invisible recaptcha component
===================
Validates your ActiveForm using Google invisible-recaptcha 

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist bariew/yii2-invisible-recaptcha "*"
```

or add

```
"bariew/yii2-invisible-recaptcha": "*"
```

to the require section of your `composer.json` file.


Usage
-----

1. Set application config params: 
```
    'params' => [
    ...
        'invisible-recaptcha-key' => '<Your Key>',
        'invisible-recaptcha-secret'  => '<Your Secret>'
    ]
```

2. Add verification attribute and a validation rule into your model rules:
```
    public $verifyCode;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ...
            ['verifyCode', InvisibleRecaptchaValidator::className(), 'message' => 'Invalid captcha value'],
        ];
    }
```
3. Use widget instead ActiiveForm submit button in your view:
```
    <?= $form->field($model, 'verifyCode')->widget(\bariew\invisibleRecaptcha\InvisibleRecaptchaWidget::className(), [
        'buttonText' => 'Save' ,
        'options' => ['class' => 'btn btn-primary'],
    ]); ?>
```