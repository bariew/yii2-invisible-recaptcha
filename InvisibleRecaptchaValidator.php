<?php
/**
 * InvisibleRecaptchaValidator class file.
 * @copyright (c) 2018, Bariev Pavel
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace bariew\invisibleRecaptcha;

use Yii;
use yii\helpers\Json;
use yii\validators\Validator;


/**
 * @author Pavel Bariev <bariew@yandex.ru>
 */
class InvisibleRecaptchaValidator extends Validator
{
    const PARAM_KEY = 'invisible-recaptcha-key';
    const PARAM_SECRET = 'invisible-recaptcha-secret';
    const POST_PARAM = 'g-recaptcha-response';
    const URL   = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * @var bool
     */
    public $skipOnEmpty = false;

    /**
     * @return string
     */
    public static function key()
    {
        return \Yii::$app->params[static::PARAM_KEY];
    }

    /**
     * @return string
     */
    public static function secret()
    {
        return \Yii::$app->params[static::PARAM_SECRET];
    }


    /**
     * @inheritdoc
     */
    protected function validateValue($value)
    {
        $valid = $this->curl([
            'secret'   => static::secret(),
            'response' => $value,
            'remoteip' => Yii::$app->request->userIP
        ]);

        return $valid ? null : [$this->message, []];
    }

    /**
     * Sends User post data with curl to google and receives 'success' if valid
     * @param array $params
     * @return bool
     */
    protected function curl(array $params)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, static::URL);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        $curlData = curl_exec($curl);
        curl_close($curl);
        return !empty(Json::decode($curlData, true)['success']);
    }
}