<script type="text/javascript">
    $(document).ready(function(){
        $("#form-help div[id^='field']").each(function()
        {
            var help = $(this);
            var side_column = $("#side-column");
            var field = help.attr('id').substr(6);

            help.hide();
            side_column.hide();

            // Find the field which matches it
            $("*[name='" + field + "']").focusin(function()
            {
                help.show();
                side_column.show();
            }).focusout(function()
            {
                side_column.hide();
                help.hide();
            });
        });
    });
</script>

<div id="form-help">
    <div id="field-slug">
        <b>Slug Naming</b>
        <p>A slug is a unique identifier for a setting. It is used in code
        to access the setting value so should be descriptive.</p>
        <ul>
            <li>Must be <b>unique</b></li>
            <li>Can contain the chars [<b>a-z 0-9 _ -</b>]</li>
            <li>Must not contain spaces</li>
        </ul>
    </div>

    <div id="field-options">
        <b>Field Options</b>
        <p>Specifies the possible values for a dropdown select list. Must be
        a comma seperated string.</p>
        <p>E.g. one,two,three</p>
    </div>

    <div id="field-validation_rules">
        <b>Field Validation</b>
        <p>A list of validation rules to apply to the field value. The list must be
        seperated by the | pipe character.</p>
        <ul>
            <li>min_length[x]</li>
            <li>max_length[x]</li>
            <li>alpha</li>
            <li>alpha_dash</li>
            <li>alpha_numeric</li>
            <li>numeric</li>
            <li>integer</li>
            <li>is_natural</li>
            <li>is_email</li>
            <li>is_ip</li>
        </ul>
        <a href="http://codeigniter.com/user_guide/libraries/form_validation.html#rulereference">Rule Reference</a>
    </div>

    <div id="field-module">
        <b>Module Naming</b>
        <p>The module name is used to group the settings. It must be lowercase chars only.</p>
    </div>
</div>