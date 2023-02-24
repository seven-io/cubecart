<form method='post' enctype='multipart/form-data'>
    <div id='Seven' class='tab_content'>
        <div style='display: flex; justify-content: space-between; align-items: center;'>
            <h1>{$LANG.module.config_settings}</h1>

            <a href='https://www.seven.io' target='_blank' title='{$LANG.seven.visit_website}'>
                {$TITLE}
            </a>
        </div>

        <fieldset>
            <legend>{$LANG.common.general}</legend>

            <div>
                <label for='status'>{$LANG.common.status}</label>
                <input
                        class='toggle'
                        id='status'
                        name='module[status]'
                        type='hidden'
                        value='{$MODULE.status}'
                />
            </div>

            <div>
                <label for='api_key'>{$LANG.seven.api_key}</label>
                <input
                        autocomplete="off"
                        class='textbox'
                        id='api_key'
                        maxlength='90'
                        name='module[token]'
                        placeholder='AA1bb2CC3ee4EE5ff6GG7HH'
                        required
                        type='password'
                        value='{$MODULE.token}'
                />
                <span>{$LANG.seven.api_key_help}</span>
            </div>
        </fieldset>

        <fieldset>
            <legend>{$LANG.seven.sms}</legend>

            <div>
                <label for='sms_from'>{$LANG.seven.from}</label>
                <input
                        class='textbox'
                        id='sms_from'
                        maxlength='16'
                        name='module[sms_from]'
                        placeholder='CubeCart'
                        value='{$MODULE.sms_from}'
                />
                <span>{$LANG.seven.from_help_sms}</span>
            </div>
        </fieldset>
    </div>

    {$MODULE_ZONES}

    <div class='form_control'>
        <input type='submit' value='{$LANG.common.save}' name='save'/>
    </div>

    <input type='hidden' name='token' value='{$SESSION_TOKEN}'/>
</form>
