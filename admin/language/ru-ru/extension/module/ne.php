<?php
// Heading
$_['heading_title'] = 'Менеджер рассылки';

// Text
$_['text_edit'] = 'Настройки';
$_['text_success'] = 'Успешно: Вы изменили модуль!';
$_['text_module'] = 'Модули';
$_['text_module_localization'] = 'Переводы';
$_['text_ne'] = 'Рассылка';
$_['text_ne_email'] = 'Создать и отправить';
$_['text_ne_draft'] = 'Черновики';
$_['text_ne_marketing'] = 'Подписчики из модуля';
$_['text_ne_subscribers'] = 'Подписчики-клиенты';
$_['text_ne_stats'] = 'Статистика';
$_['text_ne_robot'] = 'Планировщик';
$_['text_ne_template'] = 'Шаблоны';
$_['text_ne_subscribe_box'] = 'Модули';
$_['text_ne_blacklist'] = 'Черный список';
$_['text_default'] = 'По умолчанию';
$_['text_general_settings'] = 'Главные';
$_['text_throttle_settings'] = 'Разбивка рассылки';
$_['text_mailgun'] = 'MAILGUN';
$_['text_mailgun_info'] = 'Подробнее смотрите <a href="http://www.mailgun.com">на сайте Mailgun</a> и читайте <a href="http://documentation.mailgun.com">документацию</a>.';
$_['text_personalisation_tags'] = 'Используйте шорткоды: <code>{name}</code> - имя подписчика, <code>{lastname}</code> - фамилия подписчика, <code>{email}</code> в заголовке и тексте письма, они будут заменены на соответственные значения. Шорткод <code>{link}</code> будет заменен на ссылку на подтверждение.';
$_['text_cron_command'] = '/usr/bin/wget -O /dev/null -q "%s" >/dev/null 2>&1';
$_['text_help'] = '*/5 * * * *<br/><br/> *  * * * * Команда, которая будет выполнена<br/> -  - - - -<br/> |  | | | |<br/> |  | | | +- - - - дни недели (0 - 6) (Воскресенье=0)<br/> |  | | +- - - - - месяцы (1 - 12)<br/> |  | +- - - - - - дни месяца (1 - 31)<br/> |  +- - - - - - - часы (0 - 23)<br/> +- - - - - - - -  минуты (0 - 59) */5 равно "каждые 5 минут"';
$_['text_licence_info'] = '';
$_['text_licence_text'] = '';
$_['text_mail'] = 'MAIL';
$_['text_smtp'] = 'SMTP';
$_['text_smtp_settings'] = 'Mail';

// Help
$_['help_mail_protocol'] = 'Используйте функцию \'MAIL\' если она не отключена на вашем сервере.';

// Entry
$_['entry_use_throttle'] = 'Разбивка получателей рассылки на равные группы';
$_['help_use_throttle'] = 'Эта функция требует настроенный крон на сервере.';
$_['entry_throttle_emails'] = 'Количество получателей в одной группе';
$_['help_throttle_emails'] = 'На какие части разбивать получателей';
$_['entry_throttle_time'] = 'Интервал между отправкой';
$_['help_throttle_time'] = 'Время в минутах';
$_['entry_sent_retries'] = 'Retries count';
$_['help_sent_retries'] = 'Количество повторных попыток отправки рассылки на указанный адрес электронной почты.';
$_['entry_name'] = 'Имя';
$_['text_yes'] = 'Да';
$_['text_no'] = 'Нет';
$_['entry_cron_code'] = 'Задание Cron';
$_['entry_cron_help'] = 'Расшифровка задания';
$_['entry_list'] = 'Группы подписчиков';
$_['entry_weekdays'] = 'Дни недели';
$_['entry_months'] = 'Месяцы';
$_['entry_january'] = 'Январь';
$_['entry_february'] = 'Февраль';
$_['entry_march'] = 'Март';
$_['entry_april'] = 'Апрель';
$_['entry_may'] = 'Май';
$_['entry_june'] = 'Июнь';
$_['entry_july'] = 'Июль';
$_['entry_august'] = 'Август';
$_['entry_september'] = 'Сентябрь';
$_['entry_october'] = 'Октябрь';
$_['entry_november'] = 'Ноябрь';
$_['entry_december'] = 'Декабрь';
$_['entry_sunday'] = 'Воскресенье';
$_['entry_monday'] = 'Понедельник';
$_['entry_tuesday'] = 'Вторник';
$_['entry_wednesday'] = 'Среда';
$_['entry_thursday'] = 'Четверг';
$_['entry_friday'] = 'Пятница';
$_['entry_saturday'] = 'Суббота';
$_['entry_transaction_id'] = 'Order ID';
$_['entry_transaction_email'] = 'E-mail';
$_['entry_hide_marketing'] = 'Спрятать выбор группы подписчиков в форме';
$_['entry_subscribe_confirmation'] = 'Подтверждение подписки';
$_['entry_subject'] = 'Заголовок';
$_['entry_message'] = 'Сообщение';
$_['entry_website'] = 'Сайт';
$_['entry_use_smtp'] = 'Собственные настройки метода отправки';
$_['entry_mail_protocol'] = 'Протокол';
$_['entry_email'] = 'Email';
$_['entry_smtp_host'] = 'SMTP Host';
$_['entry_smtp_username'] = 'SMTP Username';
$_['entry_smtp_password'] = 'SMTP Password';
$_['entry_smtp_port'] = 'SMTP Port';
$_['entry_stores'] = 'Магазины';

// Button
$_['button_add_list'] = 'Добавить';
$_['button_activate'] = 'Активировать';
$_['button_save'] = 'Сохранить';
$_['button_cancel'] = 'Отменить';

// Error
$_['error_permission'] = 'Внимание: У вас нет доступа на управление модулем!';


