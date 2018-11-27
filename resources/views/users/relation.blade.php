@extends('base.base')

@section('base')

    <style>
        td,th{
            font-size: 16px;
            text-align: center
        }
        div .btn-group{

            margin-top: 5px;
            margin-bottom: 5px;
        }
        td {
            text-align: center;
        }
        li.list-group-item.node-tree1 {
            width: 100%;
        }
        span.badge {
            border-radius: 15px;
            font-size: 100%;
        }
    </style>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white mr-2">
                        <i class="mdi mdi-wrench"></i>
                    </span>
                    用户
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">用户管理</a></li>
                        <li class="breadcrumb-item active" aria-current="page">用户关系网</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">用户关系网</h4>
                            <div>
                                <div id="jstree"></div>
                                <div id="jstree_demo"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="{{ url('js/jquery-1.11.3.min.js') }}"></script>
    <script src="{{ url('js/jstree.js') }}"></script>


    <script>
            $('#jstree_demo').jstree({
                'core' : {
                    "themes" : {
                        "responsive": false
                    },
                    "types": {
                        "default" : {
                            "icon": "fa fa-user"
                        },
                        'nouser': {
                            "icon": 'fa fa-user-o'
                        }
                    },
                    // so that create works
                    "check_callback" : true,
                    'data' :function (obj, callback) {
                        var jsonstr="[]";
                        var jsonarray = eval('('+jsonstr+')');
                        $.ajax({
                            type: "POST",
                            url:"<?php echo e(route('users.tree_list')); ?>",
                            dataType:"json",
                            success:function(result) {
//                                console.info(result)
                                var arrays= result.data;
                                for(var i=0 ; i<arrays.length; i++){
//                                    console.log(arrays[i])
                                    var arr = {
                                        "id":arrays[i].nodeId,
//                                        "parent":"#",
                                        "text":arrays[i].text
                                    }
                                    jsonarray.push(arr);
                                }
//                                console.info(jsonarray);
                                callback.call(this, jsonarray);
                            }

                        });
//                        console.info(jsonarray)
                    },
//                    "checkbox" : {
//                        "keep_selected_style" : false
//                    },
                },
                "plugins" : ["types"]
            }).bind("select_node.jstree",function (event,data) {
                var inst = data.instance;
                var selectedNode = inst.get_node(data.selected);
                loadNodes(inst,selectedNode);
//                var level = $("#"+selectedNode.id).attr("aria-level")
            });
            function loadNodes(inst,selectedNode) {
//                if(selectedNode.)
//                console.info(selectedNode.id)
                $.ajax({
                    url:"<?php echo e(route('users.tree_list')); ?>",
                    type:'POST',
                    data:{
                        id:selectedNode.id,
                    },
                    success:function (res) {
                        if(res.data) {
                            selectedNode.children = [];
                            $.each(res.data, function (i, item) {
//                                console.info(item)
                                var obj = {text: item};
                                //$('#jstree_div').jstree('create_node', selectedNode, obj, 'last');
                                inst.create_node(selectedNode, item, "last");
                            });
                            inst.open_node(selectedNode);
                        }

                    },
                    error: function () {  // ajax请求失败
                        swal("啊哦。。。", "服务器走丢了。。。", "error");
                    }

                })
            }




    </script>
@endsection
