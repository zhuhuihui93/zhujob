define(['jquery.easyui.app', 'common/method'], function($, method) {
	var module = {
		option: {
			datagrid: null
		},

		init: function(e){
			this.option.datagrid = e;
			method.datagrid.init(e, this);
			//绑定地址获取按钮
			$(document).on("click", "#ajaxAds2l", function(){ 
					
				var ads =$("textarea[name='info[address]']").val();
				var city = $("input[name='info[area]']").val();
				var href = $(this).data('href');
				
				$.post(href, {ads: ads,city:city}, function(a){
					if(a.status==0){
						if($.isArray(a.result)){
							$("input[name='info[latitude]']").val(a.result[0].location.lat);
							$("input[name='info[longitude]']").val(a.result[0].location.lng);
						}else{
							$("input[name='info[latitude]']").val(a.result.location.lat);
							$("input[name='info[longitude]']").val(a.result.location.lng);
						}
					}else{
						alert(a.msg);
					}
				},"json");
				}); 
			//补充生成二维码 ercodes
			var cc = 1;
			$(document).on("click", "#ercodes>.ercode>img", function(){ 
				var this_img = $(this);
				var href = this_img.data('href');
				var url = this_img.data('url');
				var id = this_img.data('id');
				var fid = this_img.data('fid');

				//if(url.length>10) return false;
				
				if(cc==1){
					cc =0;
					$.post(href, {id:id,fid:fid}, function(a){
						cc = 1;
						if(a.status==0){
							this_img.attr("src",a.data);
							
						}else{
							alert(a.msg);
						}
					},"json");
				}
				
			
			});
		},

		//点击操作
		onClickCell: function(index, field, value){
			switch(field){
				//查看浏览器详情
				case 'pp':
					module.handle.detail(value);
					break;
					
			}
		},

		//对应php代码controller中的action名称
		action: {
			//添加充电桩
			ChargeAdd: function(e, row, rows){
			
				var href = $(e).data('href');
				
				method.dialog.form(e, {
					width : 400,
					height: 500,
					href  : href
				}, function(){
					//var node   = $(module.option.treegrid).treegrid('getParent', row.id);
					//var target = node ? node.id : '';
					//module.handle.refresh(target);
				
					module.handle.refresh();
				});
			},
			//添加充电桩
			ChargeEdit: function(e, row, rows){
				if(!row){
					method.messager.tip('未选择数据', 'error');
					return false;
				}
				var href = $(e).data('href');
				href += href.indexOf('?') != -1 ? '&id=' + row.id : '?id=' + row.id;
				method.dialog.form(e, {
					width : 400,
					height: 500,
					timeout:600,
					href  : href
				}, function(){
					//var node   = $(module.option.treegrid).treegrid('getParent', row.id);
					//var target = node ? node.id : '';
					//module.handle.refresh(target);
					module.handle.refresh();
				});
			},
			//删除充电桩
			ChargeDelete: function(e, row, rows){
				if(!row){
					method.messager.tip('未选择数据', 'error');
					return false;
				}
				var href = $(e).data('href');
				var ids  = [];
				for(var i = 0; i < rows.length; i++){
					ids.push(rows[i]['id']);
				}

				$.messager.confirm('系统提示', '确定要继续吗？', function (res) {
					if(!res) return false;

					method.request.post(href, {ids: ids.join(',')}, function(){
						module.handle.refresh();
					});
				});
			},
			//二维码显示于更新
			Chargeercode:function(e,row,rows){
				if(!row){
					method.messager.tip('未选择数据', 'error');
					return false;
				}
				var href = $(e).data('href');
				href += href.indexOf('?') != -1 ? '&id=' + row.id : '?id=' + row.id;
				method.dialog.form(e, {
					width : 900,
					height: 600,
					href  : href
				}, function(){
					//var node   = $(module.option.treegrid).treegrid('getParent', row.id);
					//var target = node ? node.id : '';
					//module.handle.refresh(target);
					//module.handle.refresh();
				});
			}
		},

		//其他操作
		handle: {
			//刷新
			refresh: function(){
				$(module.option.datagrid).datagrid('reload');
			},

			//查看参数详情
			detail: function(content){
				method.dialog.content(null, {
					title       : '充电桩入口',
					content     : '<pre>' + content + '</pre>',
					iconCls     : 'fa fa-file-o',
					width       : 350,
					height      : 200,
					maximizable : true,
					resizable   : true
				});
			}
		}
	};
	
	return module;
});
