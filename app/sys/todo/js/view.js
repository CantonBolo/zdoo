/* Finish a todo. */
function finishTodo(id)
{
    $.get(createLink('todo', 'finish', 'todoId=' + id, 'json'),function(response)
    {
        if(response.result == 'success')
        {
            if(response.confirm)
            {
                if(confirm(response.confirm.note))
                {   
                    $.openEntry(response.confirm.entry, response.confirm.url);
                }   
            }
        }
        else
        {
            if(response.message) $.zui.messager.show(response.message);
        }
        return false;
    }, 'json');
}

$(document).ready(function()
{
    $.setAjaxLoader('#triggerModal .ajaxEdit', '#triggerModal');
    $.setAjaxLoader('#ajaxModal .ajaxEdit', '#ajaxModal');
    $.setAjaxLoader('#triggerModal .ajaxAssign', '#triggerModal');
    $.setAjaxLoader('#ajaxModal .ajaxAssign', '#ajaxModal');

    $('.ajaxFinish').click(function()
    {
        $(this).prop('href', '');
        finishTodo($(this).data('id'));

        /* update calendar data if in calendar page. */
        var uc = window['updateCalendar'];
        if($.isFunction(uc))
        {
            updateCalendar();
            $.zui.modalTrigger.close();
        }
        else
        {
            location.reload();
        }
        return false;
    });

    $('[data-toggle=ajax]').click(function()
    {
        $.get($(this).prop('href'), function(response)
        {
            if(response.message) $.zui.messager.success(response.message);
            /* update calendar data if in calendar page. */
            var uc = window['updateCalendar'];
            if($.isFunction(uc))
            {
                updateCalendar();
                $.zui.modalTrigger.close();
            }
            else
            {
                location.reload();
            }
            return false;
        }, 'json');
        return false;
    });

    /* Adjust default deleter. */
    $.setAjaxDeleter('.todoDeleter', function(data)
    {
        if(data.result == 'success')
        {
            /* update calendar data if in calendar page. */
            var uc = window['updateCalendar'];
            if($.isFunction(uc))
            {
                updateCalendar();
                $.zui.modalTrigger.close();
            }
            else
            {
                location.reload();
            }
        }
        else
        {
            alert(data.message);
            return location.reload();
        }
        return false;
    });
});