<?php

class DepositForm extends CFormModel {

    public $PAYEE_ACCOUNT; //* номернашего кошелька
    public $PAYMENT_UNITS; //* Валюта платежа. USD, EUR, OAU. Должна соответствовать выбранному аккаунту
    public $PAYEE_NAME; //* Имя, которое видит пользователь, совершающий платеж
    public $PAYMENT_ID; //Идентификатор данного платежа. Вы можете ввести сюда любое слово или текст
    public $PAYMENT_AMOUNT; //сумма платежа
    public $STATUS_URL; //Это ULR, по мерчант будет обращаться после успешного проведения платежа.
                        //Вы можете ввести следующий - mailto:user@server.com для направления Ваших
                        //платежей на указанный e-mail.
    public $PAYMENT_URL; //* Это URL куда пользователь будет перенаправлен после успешного проведения платежа.
    public $PAYMENT_URL_METHOD; //GET / POST / LINK
    public $NOPAYMENT_URL; //* Это URL куда пользователь будет перенаправлен после неудачной попытки провести платеж.
    public $NOPAYMENT_URL_METHOD; //GET / POST / LINK
    public $SUGGESTED_MEMO; //Дополнительные поля.



    public function attributeLabels()
    {
        return array(
            'PAYMENT_AMOUNT'=>'Введите сумму в долларах (USD):',
        );
    }




}