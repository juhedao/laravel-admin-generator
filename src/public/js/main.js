/**
 * Created by Administrator on 2016/4/6.
 */
var msg = {
    success : function(text){
        $('#msg-success').find('.text').html(text);
        $('#msg-warning').hide();
        $('#msg-success').show();
        //setTimeout("$('#msg-success').fadeOut(5000);",5000);
    },
    warning : function(text){
        $('#msg-warning').find('.text').html(text);
        $('#msg-success').hide();
        $('#msg-warning').show();
        //setTimeout("$('#msg-warning').fadeOut(5000);",5000);
    }
};

var ajax = {
    getTemplates : function(path,select,callback){
        $.ajax({
            'url' : '/admin/generator/templates-ajax',
            'data' : 'F='+Math.random()+'&path='+path,
            'dataType' : 'json'
        }).done(function(data){
            if(typeof select!="undefined"&&select!=null){
                var options=['<option value="">请选择...</option>'];
                for(var i=0;i<data.length;i++){
                    options.push('<option value="'+data[i]+'">'+data[i]+'</option>');
                }
                select.html(options.join(''));
            }else{
                if (callback&&typeof callback==="function")
                callback(data);
            }
        }).fail(function(data){
            document.write(JSON.stringify(data));
        });
    },
    setConfig :function(path,value,callback){
        $.ajax({
            'type' : 'post',
            'url' : '/admin/generator/option-set-ajax',
            'data' : 'F='+Math.random()+'&option_name='+path+"&option_value="+value,
            'dataType' : 'json'
        }).done(function(data){
            msg.success(data.msg);
            if (callback && typeof callback==="function")
                callback(data);
        }).fail(function(data){
            document.write(JSON.stringify(data));
        });
    },
    getTemplateParams : function(path,box){
        box.html('');
        $.ajax({
            'url' : '/admin/generator/template-params-ajax',
            'data' : 'F='+Math.random()+'&path='+path,
            'dataType' : 'json'
        }).done(function(data){
            if(data.length>0){
                var list = ['<h4>可替换位置</h4>'];
                for(var i=0;i<data.length;i++){
                    list.push('<span>'+data[i]+' : </span> <textarea name="param['+data[i].replace(/\[/g,'').replace(/\]/g,'')+']" rows="5"></textarea>');
                }
                box.html(list.join('<br>'));
                box.show();
            }else{
                box.hide();
            }
        }).fail(function(data){
            document.write(JSON.stringify(data));
        });
    },
    getFiles : function(fileType,callback){
        $.ajax({
            'url' : '/admin/generator/files',
            'data' : 'F='+Math.random()+'&type='+fileType,
            'dataType' : 'json'
        }).done(function(data){
            if (callback && typeof callback==="function")
                callback(data);
        }).fail(function(data){
            document.write(JSON.stringify(data));
        });
    },
    deleteFiles : function(ids,callback){
        $.ajax({
            'url' : '/admin/generator/delete-files',
            'data' : 'F='+Math.random()+'&ids='+ids,
            'dataType' : 'json'
        }).done(function(data){
            if (callback && typeof callback==="function")
                callback(data);
        }).fail(function(data){
            document.write(JSON.stringify(data));
        });
    },
    copyFiles : function(type,id,path,newName,fileType,callback){
        $.ajax({
            'url' : '/admin/generator/copy-files',
            'data' : 'F='+Math.random()+'&type='+type+'&id='+id+'&path='+path+'&new-name='+newName+'&file-type='+fileType,
            'dataType' : 'json'
        }).done(function(data){
            if(data.result=='success'){
                msg.success(data.msg.join('<br>'));
            }else{
                msg.warning(data.msg.join('<br>'));
            }
            if (callback && typeof callback==="function")
                callback(data);
        }).fail(function(data){
            document.write(JSON.stringify(data));
        });
    },
    submitForm : function(form,action,callback){
        $.ajax({
            'type' : 'post',
            'url' : action,
            'data' : form.serialize(),
            'dataType' : 'json'
        }).done(function(data){
            if (callback && typeof callback==="function"){
                callback(data);
            }else{
                if(data.result=='success'){
                    msg.success(data.msg.join('<br>'));
                }else{
                    msg.warning(data.msg.join('<br>'));
                }
            }
        }).fail(function(data){
            document.write(JSON.stringify(data));
        });
        return false;
    },
    getTables : function(optionName,callback){
        $.ajax({
            'url' : '/admin/generator/tables-ajax',
            'data' : 'option-name='+optionName,
            'dataType' : 'json'
        }).done(function(data){
            if (callback && typeof callback==="function"){
                callback(data);
            }
        }).fail(function(data){
            msg.warning('数据库连接失败！');
        });
    },
    getColumns : function(optionName,tableName,callback){
        $.ajax({
            'url' : '/admin/generator/columns-ajax',
            'data' : 'option-name='+optionName+'&table-name='+tableName,
            'dataType' : 'json'
        }).done(function(data){
            if (callback && typeof callback==="function"){
                callback(data);
            }
        }).fail(function(data){
            document.write(JSON.stringify(data));
        });
    }
};

function funcChina(text){
    if(!/.*[\u4e00-\u9fa5]+.*$/.test(text))
    {
        return false;
    }
    return true;
}
function initialize(obj) {
    var text = $(obj).val();
    try{
        var p=$(obj).parents('tr');
        var c=p.find('.comment');
        if($.trim(c.val())==''){
            c.val(text);
        }
    }catch (e){

    }
    if(funcChina(text)) {
        $.ajax({
            'url': 'http://fanyi.youdao.com/openapi.do?keyfrom=laravel-maigic-gui&key=1811460558&type=data&doctype=jsonp&callback=?&version=1.1&only=dict',
            'type': 'GET',
            'dataType': 'JSONP',
            'data': 'q=' + text
        }).done(function (data) {
            try {
                $(obj).val(data.basic.explains[0].replace(/\s+/g, '_').toLowerCase());
                var id = $(obj).attr('list');
                var list = [];
                var items = data.basic.explains;
                for (var i = 0; i < items.length; i++) {
                    var str=items[i].replace(/\s+/g, '_').toLowerCase();
                    list.push('<option label="' + str + '" value="' + str + '" />');
                }
                $('#' + id).html(list.join(''));
                $('#' + id).show();
            } catch (e) {

            }

        }).fail(function (data) {
            $(obj).attr('placeholder', '翻译失败');
        });
    }
}

$(document).ready(function(){
    $(document).on('blur','.translation',function(){
        initialize(this);
    });
});






