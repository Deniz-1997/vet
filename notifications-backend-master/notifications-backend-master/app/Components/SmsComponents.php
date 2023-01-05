<?php

namespace App\Components;

use stdClass;

const SMS_RU_LINK = 'https://sms.ru/';
const SMS_SEND_ENDPOINT = '/sms/send';

/**
 * Класс для работы с API сайта sms.ru для PHP 7
 * Разработчик WebProgrammer (kl.dm.vl@yandex.ru), легкие корректировки - Роман Гудев <rgudev@bk.ru>
 *
 * Профилирование Осокин Вадим osok.vadim@gmail.com
 *
 */
class SmsComponents
{
    private $ApiKey;

    private $count_repeat = 5;    //количество попыток достучаться до сервера если он не доступен

    /**
     * SmsComponents constructor.
     * @param $ApiKey
     */
    public function __construct($ApiKey)
    {
        $this->ApiKey = $ApiKey;
    }

    /**
     * Совершает отправку СМС сообщения одному или нескольким получателям.
     *
     * @param $post
     *   $post->to = string - Номер телефона получателя (либо несколько номеров, через запятую — до 100 штук за один запрос). Если вы указываете несколько номеров и один из них указан неверно, то на остальные номера сообщения также не отправляются, и возвращается код ошибки.
     *   $post->msg = string - Текст сообщения в кодировке UTF-8
     *   $post->multi = array('номер получателя' => 'текст сообщения') - Если вы хотите в одном запросе отправить разные сообщения на несколько номеров, то воспользуйтесь этим параметром (до 100 сообщений за 1 запрос). В этом случае, параметры to и text использовать не нужно
     *   $post->from = string - Имя отправителя (должно быть согласовано с администрацией). Если не заполнено, в качестве отправителя будет указан ваш номер.
     *   $post->time = Если вам нужна отложенная отправка, то укажите время отправки. Указывается в формате UNIX TIME (пример: 1280307978). Должно быть не больше 7 дней с момента подачи запроса. Если время меньше текущего времени, сообщение отправляется моментально.
     *   $post->translit = 1 - Переводит все русские символы в латинские. (по умолчанию 0)
     *   $post->test = 1 - Имитирует отправку сообщения для тестирования ваших программ на правильность обработки ответов сервера. При этом само сообщение не отправляется и баланс не расходуется. (по умолчанию 0)
     *   $post->partner_id = int - Если вы участвуете в партнерской программе, укажите этот параметр в запросе и получайте проценты от стоимости отправленных сообщений.
     * @return array|mixed|stdClass
     */
    public function send($post)
    {
        $request = $this->_request(SMS_SEND_ENDPOINT, $post);
        $resp = $this->checkReplyError($request);
        if ($resp->status === 'OK') {
            $temp = (array)$resp->sms;
            unset($resp->sms);
            $temp = array_pop($temp);
            if ($temp) {
                return $temp;
            }
            return $resp;
        }
        return $resp;
    }

    /**
     * Отправка на несколько номеров
     *
     * @param $post
     * @return stdClass|mixed
     */
    public function multiSend($post)
    {
        $request = $this->_request(SMS_SEND_ENDPOINT, $post);
        return $this->checkReplyError($request);
    }

    /**
     * Отправка СМС сообщений по электронной почте
     * @param $post
     *   $post->from = string - Ваш электронный адрес
     *   $post->charset = string - кодировка переданных данных
     *   $post->send_charset = string - кодировка переданных письма
     *   $post->subject = string - тема письма
     *   $post->body = string - текст письма
     * @return bool
     */
    public function sendSmtp($post): bool
    {
        $post->to = $this->ApiKey . '@sms.ru';
        $post->subject = $this->_smsMineHeaderEncode($post->subject, $post->charset, $post->send_charset);
        if ($post->charset !== $post->send_charset) {
            $post->body = iconv($post->charset, $post->send_charset, $post->body);
        }
        $headers = "From: $post->\r\n";
        $headers .= "Content-type: text/plain; charset=$post->send_charset\r\n";
        return mail($post->to, $post->subject, $post->body, $headers);
    }

    /**
     * Возвращает статус отправленных сообщений
     *
     * @param $id
     * @return mixed|stdClass
     */
    public function getStatus($id)
    {
        $post = new stdClass();
        $post->sms_id = $id;
        $request = $this->_request('sms/status', $post);
        return $this->checkReplyError($request);
    }

    /**
     * Возвращает стоимость сообщения на указанный номер и количество сообщений, необходимых для его отправки.
     * @param $post
     *   $post->to = string - Номер телефона получателя (либо несколько номеров, через запятую — до 100 штук за один запрос) Если вы указываете несколько номеров и один из них указан неверно, то возвращается код ошибки.
     *   $post->text = string - Текст сообщения в кодировке UTF-8. Если текст не введен, то возвращается стоимость 1 сообщения. Если текст введен, то возвращается стоимость, рассчитанная по длине сообщения.
     *   $post->translit = int - Переводит все русские символы в латинские
     * @return mixed|stdClass
     */
    public function getCost($post)
    {
        $request = $this->_request('sms/cost', $post);
        return $this->checkReplyError($request);
    }

    /**
     * Получение состояния баланса
     *
     * @return mixed|stdClass
     */
    public function getBalance()
    {
        $request = $this->_request('my/balance');
        return $this->checkReplyError($request);
    }

    /**
     * Получение текущего состояния вашего дневного лимита.
     *
     * @return mixed|stdClass
     */
    public function getLimit()
    {
        $request = $this->_request('my/limit');
        return $this->checkReplyError($request);
    }

    /**
     * Получение списка отправителей
     *
     * @return mixed|stdClass
     */
    public function getSenders()
    {
        $request = $this->_request('my/senders');
        return $this->checkReplyError($request);
    }

    /**
     * На номера, добавленные в стоплист, не доставляются сообщения (и за них не списываются деньги)
     * @param string $phone Номер телефона.
     * @param string $text Примечание (доступно только вам).
     * @return mixed|stdClass
     */
    public function addStopList($phone, $text = "")
    {
        $post = new stdClass();
        $post->stoplist_phone = $phone;
        $post->stoplist_text = $text;
        $request = $this->_request('stoplist/add', $post);
        return $this->checkReplyError($request);
    }

    /**
     * Удаляет один номер из стоплиста
     *
     * @param string $phone Номер телефона.
     * @return mixed|stdClass
     */
    public function delStopList($phone)
    {
        $post = new stdClass();
        $post->stoplist_phone = $phone;

        $request = $this->_request('stoplist/del', $post);
        return $this->checkReplyError($request);
    }

    /**
     * Получить номера занесённые в стоплист
     */
    public function getStopList()
    {
        $request = $this->_request('stoplist/get');
        return $this->checkReplyError($request);
    }

    /**
     * Добавить URL Callback системы на вашей стороне, на которую будут возвращаться статусы отправленных вами сообщений
     * @param $post
     *    $post->url = string - Адрес обработчика (должен начинаться на http://)
     * @return mixed|stdClass
     */
    public function addCallback($post)
    {
        $request = $this->_request('callback/add', $post);
        return $this->checkReplyError($request);
    }

    /**
     * Удалить обработчик, внесенный вами ранее
     * @param $post
     *   $post->url = string - Адрес обработчика (должен начинаться на http://)
     * @return mixed|stdClass
     */
    public function delCallback($post)
    {
        $request = $this->_request('callback/del', $post);
        return $this->checkReplyError($request);
    }

    /**
     * Все имеющиеся у вас обработчики
     *
     * @return mixed|stdClass
     */
    public function getCallback()
    {
        $request = $this->_request('callback/get');
        return $this->checkReplyError($request);
    }

    private function _request($endpoint, $post = FALSE)
    {
        $url = SMS_RU_LINK . trim($endpoint, '/') . '/';

        if ($post) {
            $r_post = $post;
        }

        $ch = curl_init($url . '?json=1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        if (!$post) {
            $post = new stdClass();
        }

        if (empty($post->api_id)) {
            $post->api_id = $this->ApiKey;
        }

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query((array)$post));

        $body = curl_exec($ch);

        if ($body === FALSE) {
            $error = curl_error($ch);
        } else {
            $error = FALSE;
        }

        curl_close($ch);

        if ($error && $this->count_repeat > 0) {
            $this->count_repeat--;
            return $this->_request($url, $r_post);
        }
        return $body;
    }

    /**
     * @param $res
     * @return mixed|stdClass
     */
    private function checkReplyError($res)
    {
        if (!$res) {
            $temp = new stdClass();
            $temp->status = 'ERROR';
            $temp->status_code = '000';
            $temp->status_text = 'Невозможно установить связь с сервером.';
            $temp->result = $res;
            return $temp;
        }

        $result = json_decode($res);

        if (!$result || !$result->status) {
            $temp = new stdClass();
            $temp->status = 'ERROR';
            $temp->status_code = '000';
            $temp->status_text = 'Невозможно установить связь с сервером.';
            $temp->result = $res;
            return $temp;
        }

        return $result;
    }

    /**
     * @param $str
     * @param $post_charset
     * @param $send_charset
     * @return string
     */
    private function _smsMineHeaderEncode($str, $post_charset, $send_charset): string
    {
        if ($post_charset !== $send_charset) {
            $str = iconv($post_charset, $send_charset, $str);
        }
        return '=?' . $send_charset . '?B?' . base64_encode($str) . '?=';
    }
}
