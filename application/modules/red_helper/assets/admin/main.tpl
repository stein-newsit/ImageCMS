<div id="setingsModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"> 
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>{lang('Settings', 'red_helper')}</h3>
    </div>
    <div class="modal-body">
        <form method="post" action="/admin/components/init_window/red_helper/saveSettings" enctype="multipart/form-data" id="settingsForm">
            <div>
                <label for="login">{lang('Your login', 'red_helper')}:</label>
                <input name="login" type="text" id="login" value="{$login}"/>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" onclick="$('#setingsModal').modal('hide')">{lang("Close", 'red_helper')}</a>
        <button class="btn btn-primary formSubmit submitButton" 
                data-form="#settingsForm" 
                data-submit 
                data-dismiss="modal" 
                aria-hidden="true">{lang("Save settings", 'red_helper')}
        </button>
    </div>
</div>

<div id="registerModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">    
    <form class="redHelpForm">
        <input type="hidden" name="ref" value="1">
        <input type="hidden" name="locale" value="en">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>{lang("Registration", 'red_helper')}</h3>
        </div>
        <div class="modal-body">
            <div>
                <label for="login"><b>{lang('Your login', 'red_helper')}:</b> *</label>
                <input type="text" id="login1" name="login1" value=""/>
                <p class="help-block">{lang('Create your own unique login. Username can only consist of letters and numbers, login itself must be at least 3 characters.', 'red_helper')}</p>
            </div>
            <div>
                <label for="pass"><b>{lang('Choose password', 'red_helper')}:</b> *</label>
                <input type="password" id="pass" name="pass" value=""/>
                <p class="help-block">{lang('Use a password to connect service RedHelper. For security reasons, the password should be different from the password ImageCMS.', 'red_helper')}</p>
            </div>        
            <div>
                <label for="email"><b>{lang('Your email', 'red_helper')}:</b> *</label>
                <input type="text" id="email" name="email" value="" />
                <p class="help-block">{lang('Enter your email address to be used for communication with you.', 'red_helper')}</p>
            </div>
            <div>
                <label for="pass"><b>{lang('Your name', 'red_helper')}:</b> *</label>
                <input type="text" id="name" name="name" value=""/>
                <p class="help-block">{lang('Enter your name.', 'red_helper')}</p>
            </div>   
            <div>
                <label for="pass"><b>{lang('Your phone', 'red_helper')}:</b> *</label>
                <input type="text" id="phone" name="phone" value="" />
                <p class="help-block">{lang('Your contact phone number.', 'red_helper')}</p>
            </div> 
            <p class="help-block"><b>{lang('* - Fields are required', 'red_helper')}</b></p>
            <div>
                <p id="result" style="color:red"></p>
                
            </div>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn" onclick="$('#registerModal').modal('hide')">{lang("Close", 'red_helper')}</a>
            <a href="#" id="go" class="btn btn-primary">{lang("Registration", 'red_helper')}</a>
        </div>
        {form_csrf()}
    </form>
</div>
<section class="mini-layout">
    <div class="frame_title clearfix">
        <div class="pull-left">
            <span class="help-inline"></span>
            <span class="title">{lang('Red Helper', 'red_helper')}</span>
            <div style="display:inline-block; vertical-align: top; padding-top:6px;padding-left:10px;" class="btn-group myTab " data-toggle="buttons-radio">
                <a href="#row-fluid-1" class="btn btn-small active">Что такое RedHelper?</a>
                <a href="#row-fluid-2" class="btn btn-small">Установка модуля RedHelper</a>
            </div>
        </div>
        <div class="pull-right">
            <div class="d-i_b">
                <a href="/admin/components/modules_table" class="t-d_n m-r_15"><span class="f-s_14">←</span> <span class="t-d_u">{lang("Back", 'admin')}</span></a>
            </div>
            <div class="d-i_b">
                <a data-toggle="modal" href="#setingsModal" class="btn btn-primary btn-small">
                    {lang('Settings', 'red_helper')}
                </a>
                <a data-toggle="modal" href="#registerModal" class="btn btn-primary btn-small">
                    {lang('Registration', 'red_helper')}
                </a>
            </div>
        </div>                            
    </div>
    <div class="tab-content">
        <div class="row-fluid tab-pane active" id="row-fluid-1">
            <p></p>
            <img src="http://i0.redhelper.ru/media/graphics/logo_flat3.png" />
            <p></p>
            <h4>Что такое RedHelper?</h4>
            <p>RedHelper - это SaaS решение для Интернет сайтов. Самый популярный сервис для увеличения конверсии и продаж.</p>
            <p>Информация о RedHelper на сайте компании - <a target = "_blank" href="http://redhelper.ru/">http://redhelper.ru/</a>
            <p><a target = "_blank" href="http://youtu.be/9EDo6zQJJGM">Видеопрезентация</a></p>
            <p>Этот модуль позволит Вам самостоятельно подключить систему RedHelper для Вашего сайта ImageCMS, без помощи технического специалиста.</p>
            <h4>С чего начать работу?</h4>
            <p>Вам необходимо выбрать вариант подключения:</p>
            <p>a. Существующий аккаунт в системе RedHelper</p>
            <p>Если Вы знаете свой логин в системе, нажмите на кнопку "Параметры", введите его.</p>
            <p>b. Создать новый аккаунт в RedHelper, нажмите на кнопку "Регистрация"</p>
        </div>
        <div id="row-fluid-2" class="tab-pane">
            <p></p>
            <img src="http://i0.redhelper.ru/media/graphics/logo_flat3.png" />
            <p></p>
            <h4>Поздравляем. Вы установили онлайн-консультант RedHelper на свой сайт!</h4> 
            <p>Теперь Вам необходимо установить приложение для оператора и настроить поведение системы в своём личном кабинете <a target = "_blank" href="http://redhelper.ru/my/{$login}">Redhelper</a>.</p>

            <p><b>1. Установка приложения.</b></p>

            <p>Вам необходимо скачать и установить приложение оператора. Приложение оператора позволяет наблюдать за посетителями, отвечать на их вопросы и многое другое. Приложение под Windows или Mac OS вы можете скачать по ссылкам ниже. Для других операционных систем и мобильных устройств вы можете использовать любой jabber клиент (как это сделать? – добавить ссылку https://redhelper.zendesk.com/forums/21556502-jabber-redhelper ).</p>
            <p>Выберете тип операционной системы, скачайте и установите приложение.</p>
            <p>Сделать скачивание ввиде кнопок, пример, текст, и ссылки ниже. (Картинки в аттаче)</p>
            <p>Скачать для Windows (http://update.redhelper.ru/RedHelperSetup.ru-RU.exe)</p>
            <p>Скачать для MAC OS (http://update.redhelper.ru/RedHelper.dmg)</p>


            <p>После установки используйте логин и пароль, выбранный при регистрации, для входа в приложение. Для завершения установки, попробуйте пообщаться через приложение. Для этого зайдите на сайт (обновите страницу), нажмите на значок "Задать вопрос" и напишите пару фраз в открывшемся окне. Сообщения придут в приложение оператора.</p>
            <p><b>2. Настройка системы</b></p>
            <p>Всё готово для использования RedHelper на вашем сайте, но для максимальной эффективности работы системы необходимо произвести настройку внешнего вида и поведения RedHelper для вашего сайта. </p>

            <p>Добавить кнопку перехода в ЛК RedHelper – Перейти в Личный кабинет RedHelper (http://redhelper.ru/my/{$login}) </p>

            <p>Подпись к кнопке (Цвет подписи серый, шрифт курсивом.) Используйте в дальнейшем для перехода в Личный кабинет наш сайт  http://redhelper.ru/</p>

            <p>Если Вам требуется помощь, напишите или позвоните нам.</p>
            <p>Отдел технической поддержки клиентов RedHelper.</p>

            <p>+7 (495) 221-77-57,  (доб. 201)</p>

            <p>Info@redhelper.ru</p>

            <p>База знаний центра технической поддержки RedHelper</p>

            <p>Вы так же можете получить поддержку в режиме онлайн на нашем сайте: http://redhelper.ru/</p>
        </div>
    </div>
</section>
