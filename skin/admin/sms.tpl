<form action='{$VAL_SELF}' enctype='multipart/form-data' method='post' name='sms'>
    <div class='tab_content'>
        <fieldset>
            <legend>{$LANG.common.general}</legend>

            <div>
                <label for='from'>{$LANG.seven.from}</label>
                <input
                        class='textbox'
                        id='from'
                        maxlength='16'
                        name='from'
                        value='{$SEVEN.sms_from}'
                />
                <small>{$LANG.seven.from_help_sms}</small>
            </div>

            <div>
                <label for='to'>{$LANG.seven.to}</label>
                <input class='textbox' id='to' name='to' required value='{$CUSTOMER.mobile}'/>
                <small>{$LANG.seven.to_help_sms}</small>
            </div>

            <div>
                <label for='text'>{$LANG.seven.text}</label>
                <textarea
                        class='textbox'
                        id='text'
                        maxlength='1520'
                        name='text'
                        required
                        rows='6'
                        style='vertical-align: top'
                ></textarea>
                <small>{$LANG.seven.text_help_sms}</small>
            </div>
        </fieldset>

        <fieldset>
            <legend>{$LANG.common.advanced}</legend>

            <div>
                <label for='foreign_id'>{$LANG.seven.foreign_id}</label>
                <input class='textbox' id='foreign_id' maxlength='64' name='foreign_id'/>
                <small>{$LANG.seven.foreign_id_help}</small>
            </div>

            <div>
                <label for='label'>{$LANG.seven.label}</label>
                <input class='textbox' id='label' maxlength='100' name='label'/>
                <small>{$LANG.seven.label_help}</small>
            </div>

            <div>
                <label for='flash'>{$LANG.seven.flash}</label>
                <input id='flash' name='flash' type='checkbox' value='1'/>
                <small>{$LANG.seven.flash_help}</small>
            </div>

            <div>
                <label for='performanceTracking'>{$LANG.seven.performance_tracking}</label>
                <input
                        id='performanceTracking'
                        name='performance_tracking'
                        type='checkbox'
                        value='1'
                />
                <small>{$LANG.seven.performance_tracking_help}</small>
            </div>
        </fieldset>
    </div>

    <div class='form_control'>
        <button class='button'>{$LANG.common.send}</button>
    </div>

    {if not empty($CUSTOMER)}
        <h2>{$LANG.seven.placeholders}</h2>
        <ul>
            {foreach from=$CUSTOMER item=v key=k}
                <li>{literal}{{{/literal}{$k}{literal}}}{/literal} => {$v}</li>
            {/foreach}
        </ul>
    {/if}

    <input name='sms' type='hidden' value='1'/>
    <input name='token' type='hidden' value='{$SESSION_TOKEN}'/>
</form>

<script>
    const customer = {$CUSTOMER|@json_encode}

    {literal}
    const keys = Object.keys(customer)
    const regexp = new RegExp(`{{(${keys.join('|')})}}`, 'g')

    document.forms.namedItem('sms').elements.namedItem('text').addEventListener('input', e => {
        let text = e.currentTarget.value.toString()
        const arr = [...text.matchAll(regexp)]

        arr.forEach(([placeholder, key]) => text = text.replaceAll(placeholder, customer[key]))

        e.currentTarget.value = text
    })
    {/literal}
</script>
