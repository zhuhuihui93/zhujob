define(['jquery.easyui.app', 'common/method'], function($, method) {
	var module = {
		option: {
			datagrid: null
		},

		init: function(e){
			this.option.datagrid = e;

			method.datagrid.init(e, this);
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
			//删除数据
			cduserEdit: function(e, row, rows){
				if(!row){
					method.messager.tip('未选择数据', 'error');
					return false;
				}
				
				var href = $(e).data('href');
				href += href.indexOf('?') != -1 ? '&id=' + row.id : '?id=' + row.id;

				method.dialog.form(e, {
					width : 400,
					height: 400,
					href  : href
				}, function(){
					//var node   = $(module.option.treegrid).treegrid('getParent', row.id);
					//var target = node ? node.id : '';
					//module.handle.refresh(target);
					module.handle.refresh();
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
