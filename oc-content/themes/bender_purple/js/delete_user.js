$(document).ready(function(){
    $(".opt_delete_account a").click(function(){
        $("#dialog-delete-account").dialog('open');
    });

    $("#dialog-delete-account").dialog({
        autoOpen: false,
        modal: true,
        buttons: [
            {
                text: bender_purple.langs.delete,
                click: function() {
                    window.location = bender_purple.base_url + '?page=user&action=delete&id=' + bender_purple.user.id  + '&secret=' + bender_purple.user.secret;
                }
            },
            {
                text: bender_purple.langs.cancel,
                click: function() {
                    $(this).dialog("close");
                }
            }
        ]
    });
});