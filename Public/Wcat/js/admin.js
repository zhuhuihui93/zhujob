var locationname = '/Wcat/';
/* 发送验证码 */
$('#binding_phone_code').click(function(){
	var reg= /^1[3|4|5|7|8]\d{9}$/;
	var phone = $("input[name='phone']").val();
	if(!reg.test(phone)){
		mui('#popover2').popover('show');
		data1.innerHTML= "手机号格式不正确！";
		return false;
	}
	$.post(locationname+"Public/phonecode",{'phone':phone}, function(data){
    	if(data['code']==200){
    		mui('#popover2').popover('show');
			data1.innerHTML= "发送成功！";
        }else{
        	mui('#popover2').popover('show');
			data1.innerHTML= "发送失败！";
        }
	},'json');
});
/* 绑定手机号 */
$('#PhoneSubmit').click(function(){
	$.ajax({
	    cache: true,
	    type: "POST",
	    url:locationname+"Users/binding_phone",
	    data:$('#PhoneFormId').serialize(),
	    async: false,
	    dataType : "json",
	    error: function(request) {
	    },
	    success: function(data) {
	        if(data['code']==200){
	        	mui('#popover2').popover('show');
				data1.innerHTML= data['msg'];
	        	$(location).attr('href', locationname+'Users/users');
	        }else{
	        	mui('#popover2').popover('show');
				data1.innerHTML= data['msg'];
	        }
	    }
	});
});

