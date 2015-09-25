<?php include "config/config.php"; ?>

<header>
    <div class="top">
        <div class="content center top">
            <nav style="text-align: right;word-spacing: 1rem;">
                <?php
                // 检测是否有相关Cookie，如有则自动登录
                if (!isset($_SESSION['username']) &&
                    isset($_COOKIE['username']) && isset($_COOKIE['usergroup'])
                ) {
                    $_SESSION['username'] = $_COOKIE['username'];
                    $_SESSION['usergroup'] = $_COOKIE['usergroup'];
                    $_SESSION['logintype'] = "autologin";
                }
                // 检测登录状态，显示相关链接
                if (isset($_SESSION['username'])) {
                    echo $_SESSION['username'] . ' ';
                    echo '<a href="'.ROOT.'logout.php">退出</a>';
                } else {
                    echo '<a href="'.ROOT.'login.php">登录</a> ';
                    echo '<a href="'.ROOT.'register.php">注册</a>';
                }
                ?>
                <a href="<?=ROOT?>admin/index.php">管理员页面</a>
            </nav>
        </div>
    </div>
    <div class="content center">
        <div class="v-middle">
            <a href="<?=ROOT?>index.php" class="logo">天天商城</a>
        </div>
        <div class="v-middle" id="search" style="margin-left: 100px">
            <input id="keyword" name="keyword" type="text" placeholder="iPhone" autocomplete="off"/><button>搜索</button>
            <div id="suggestion" style="display: none">
            </div>
        </div>
    </div>
    <div class="bar">
        <div class="content center bar" style="height: 60px">
        </div>
    </div>
</header>

<script>

    $(document).ready(function(){
        var xhr=null;
        $("#keyword").keyup(function(){
            if(xhr){
                xhr.abort();
            }
            var inputText= $.trim(this.value);
            if(inputText!=""){
                xhr=$.ajax({
                        type: "GET",
                        url: "<?=ROOT?>service/suggestion.php",
                        cache: false,
                        data: "keyword=" + inputText,
                        dataType: "json",
                        success: function (json) {
                            if(xhr) {
                                if (json.length != 0) {

                                    var lists = "<ul>";
                                    $.each(json, function () {
                                        lists += "<li>" + this.pd_name + "</li>";
                                    });
                                    lists+="</ul>";

                                    $("#suggestion").html(lists).show();

                                    $("li").click(function () {
                                        $("#keyword").val($(this).text());
                                        $("#suggestion").hide();
                                    });
                                } else {
                                    $("#suggestion").hide();
                                }
                            }
                        }
                });
            }else{
                $("#suggestion").hide();
            }
        }).blur(function(){
            $("#suggestion").hide();
    });
    });

</script>




<!--<script>

    $(document).ready(function(){

        $('#keyword').keyup(function(){

            $("#suggestion ul").empty();
            $("#suggestion").hide();

            if (/^\s*$/.test(this.value)) return false;

            $.ajax({
                type:"GET",
                url:"<?/*=ROOT*/?>service/suggestion.php",
                cache:false,
                data:{keyword:$.trim(this.value)},
                dataType:"json",
                success:function(json){

                    if (json.length==0) return false;

                    $("#suggestion ul").empty();
                    $("#suggestion").show();
                    $.each(json,function(){
                        $("#suggestion ul").append("<li>"+this.pd_name+"</li>");
                    });
                    $("li").click(function(){
                        $("#keyword").val($(this).text());
                        $("#suggestion").hide();
                    });
                }
            });

        });

    }).click(function(){$("#suggestion").hide();});



</script>-->