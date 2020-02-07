<html>
    <head>

        <link rel="stylesheet" href="/templates/ian_admin/gfx/import.css">
<!--        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>-->
        <script>$(document).ready(function () {
                $('.dropdown-btn').on("click", function (e) {
                    if ($(this).next('ul.dropdown-menu').is(":visible")) {
                        $('.active', $(this).closest('ul')).removeClass('active');
                        $('ul.dropdown-menu', $(this).closest('ul')).hide(500);
                        $(this).next('ul.dropdown-menu').hide(500);
                        e.stopPropagation();
                        e.preventDefault();
                        //$(this).find('.active').removeClass("active");

                    } else {
                        $(this).addClass("active");
                        $(this).next('ul.dropdown-menu').show(500);
                        e.stopPropagation();
                        e.preventDefault();
                    }
                });
            });
        </script>
        <script>
            var tId;
            var count = $(".message-box .message").length;
            newMessage("HIBAAA", "HELLO W", "");
            function newMessage(type, title, txt, link) {
                $('.message-box .message-box-in').prepend("<div class='message " + type + "' style='display:none;'><div class='message-in'><h2 class='message-head'>" + title + "</h2><p class='message-text'>" + txt + "</p></div></div>");
                $(".message").each(function (index) {
                    if ($(this).is(":hidden")) {
                        $(this).show("slide", {direction: "right"}, 1000);
                    }
                });
                hideMessage();
            }
            function hideMessage() {
                tId = setTimeout(function () {
                    $(".message-box .message").eq(0).hide("slide", {direction: "right"}, 1000);
                    setTimeout(function () {
                        $('.message-box .message').eq(0).remove();
                    }, 1000);
                    if ($(".message-box .message").length > 0) {
                        clearTimeout(tId);
                        hideMessage();
                    }
                }, 15000);
            }

            function getUrlParameter(sParam) {
                var sPageURL = window.location.search.substring(1),
                        sURLVariables = sPageURL.split('&'),
                        sParameterName,
                        i;

                for (i = 0; i < sURLVariables.length; i++) {
                    sParameterName = sURLVariables[i].split('=');

                    if (sParameterName[0] === sParam) {
                        return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                    }
                }
            }
            ;
            var AjaxCall; //Global Ajax variable
            function Ajax(obj) {

                /*ProgressBar*/
                var bar = $('.bar');
                var percent = $('.percent');

                if (typeof (timeoutID) != "undefined" && timeoutID !== null) {
                    clearTimeout(timeoutID);
                }
                var url = $(obj).closest('form').data("url");
                var method = $(obj).closest('form').data("method");
                var progressbar = $(obj).closest('form').data("progressbar");
                var datatags = $(obj).closest('form').data();

                if (typeof (progressbar) != "undefined" && progressbar !== null) {
                    percent = $(progressbar);
                }
                var waiting = $(obj).closest('form').data("waiting");
                if (typeof (waiting) == "undefined" && waiting == null) {
                    var waiting = 1500;
                }
                timeoutID = setTimeout(function () {
                    urlParameter = getUrlParameter('params');
                    var formData = new FormData($(obj).closest('form')[0]);
                    if (typeof (urlParameter) == "undefined" && urlParameter == null) {
                        urlParameter = "";
                    }
                    $.each(datatags, function (key, value) {
                        formData.append("__" + key + "__", value);
                    });
                    formData.append('__urlparams__', urlParameter);
                    var ttlSize = 0;
                    $(obj).closest('form').find('input[type="file"]').each(function () {
                        ttlSize += this.files[0].size;
                    });
                    var maxSize = Math.ceil(ttlSize / 1048576) + Math.ceil($(obj).closest('form').not("[type='file']").serialize().length / 1048576);
                    if (maxSize => 5) {
                        formData.append('__uploadmaxsize__', maxSize + 10);
                    } else {
                        formData.append('__uploadmaxsize__', 10);
                    }
                    AjaxCall = $.ajax({
                        type: "POST",
                        url: url,
                        dataType: "json",
                        processData: false,
                        contentType: false,
                        data: formData,
                        beforeSend: function (data, textStatus) {

                            /*ProgressBar*/
                            var percentVal = '0%';
                            bar.width(percentVal);
                            percent.css("background-color", "red");
                            percent.html(percentVal);
                            percent.width(percentVal);
                        },
                        xhr: function () {
                            var xhr = new window.XMLHttpRequest();
                            xhr.upload.addEventListener("progress", function (evt) {
                                if (evt.lengthComputable) {
                                    var percentComplete = (evt.loaded / evt.total) * 100;

                                    if (percentComplete == 100) {
                                        var prevent_leave = false;
                                    } else {
                                        var prevent_leave = true;

                                    }
                                    console.log("adsadas");
                                    $(window).on('beforeunload', function () {
                                        if (prevent_leave) {
                                            return "Your files are not completely uploaded...";
                                        }
                                    });
                                    var percentVal = Math.round(percentComplete) + '%';
                                    percent.width(percentVal);
                                    percent.width(percentVal);
                                    percent.html(percentVal);
                                }
                            }, false);
                            return xhr;
                        },
                        success: function (data, textStatus) {

                            $.each(data.html, function (key, value) {
                                if (value.mode == "append") {
                                    $(jqID(key)).append(value.html)
                                } else if (value.mode == "override") {
                                    $(jqID(key)).html(value.html)
                                } else if (value.mode == "confirm") {
                                    $("body").append(value.html)
                                }
                            });
                            console.log(data.message);
                            $.each(data.message, function (key, value) {
                                var type = "";
                                var title = "";
                                var text = "";
                                var link = "";
                                if (typeof (value.type) != "undefined" && value.type !== null) {
                                    type = value.type;
                                }
                                if (typeof (value.title) != "undefined" && value.title !== null) {
                                    title = value.title;
                                }
                                if (typeof (value.text) != "undefined" && value.text !== null) {
                                    text = value.text;
                                }
                                if (typeof (value.link) != "undefined" && value.link !== null) {
                                    link = value.link;
                                }
                                newMessage(type, title, text, link);
                            });

                            var domain = window.location.origin;
                            var url = window.location.pathname;
                            var params = getUrlParameter("params");

                            if (typeof (data.__url__) != "undefined" && data.__url__ !== null) {
                                url = data.__url__;
                            }
                            if (typeof (data.__domain__) != "undefined" && data.__domain__ !== null) {
                                domain = data.__domain__;
                            }
                            if (typeof (data.__url_params__) != "undefined" && data.__url_params__ !== null) {
                                params = '?params=' + data.__url_params__;
                            } else if (typeof (params) != "undefined" && params !== null) {
                                params = '?params=' + params;

                            } else {
                                params = "";
                            }
                            window.history.replaceState({}, domain, url + params);

                            percent.css("background-color", "transparent");
                            bar.width("0%");
                            percent.html("");
                        },
                        error: function (req, status, err) {
                            newMessage(status, "Error", err, "");
                            console.log('Something went wrong', status, err);
                        }
                    });
                }, waiting);
            }
            function jqID(myid) {

                return "#" + myid.replace(/(:|\.|\[|\]|,|=|@)/g, "\\$1");

            }
            var getUrlParameter = function getUrlParameter(sParam) {
                var sPageURL = window.location.search.substring(1),
                        sURLVariables = sPageURL.split('&'),
                        sParameterName,
                        i;

                for (i = 0; i < sURLVariables.length; i++) {
                    sParameterName = sURLVariables[i].split('=');

                    if (sParameterName[0] === sParam) {
                        return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                    }
                }
            };
            function prevPage(obj) {
                var inp = $(obj).closest('form').find("input[name='pagenumber']");
                var val = parseInt(inp.val());
                if ((val - 1) > 0) {
                    inp.val(val - 1);
                    Ajax(obj);
                }
            }
            function nextPage(obj) {
                var inp = $(obj).closest('form').find("input[name='pagenumber']");
                var max = $(obj).closest('form').find("#max");
                var val = parseInt(inp.val());
                var maxval = parseInt(max.val());
                if ((val + 1) <= maxval) {
                    inp.val(val + 1);
                    Ajax(obj);
                }
            }
            function firstPage(obj) {
                var inp = $(obj).closest('form').find("input[name='pagenumber']");
                inp.val(1);
                Ajax(obj);
            }
            function lastPage(obj) {
                var inp = $(obj).closest('form').find("input[name='pagenumber']");
                var max = $(obj).closest('form').find("#max");
                var maxval = parseInt(max.val());
                inp.val(maxval);
                Ajax(obj);
            }
            $(document).ready(function () {
                $("form.ajax .submit").on('click', function () {
                    var form = $(this).closest("form");
                    Ajax(form);
                });
                $('.message-box .message').mouseenter(function () {
                    clearTimeout(tId);
                    console.log("mose");
                });
                $('.message-box .message').mouseleave(function () {
                    console.log("mose1");
                    hideMessage();
                });
                $('.close-confirm').on("click", function () {
                    $(this).closest(".confirm").remove();
                });
                /*$(function () {
                 $('form#ajax').attr('onsubmit', 'return false');
                 });
                 $(function () {
                 $('.settingsajax').attr('onsubmit', 'return false');
                 });
                 $(function () {
                 $('form#pager').attr('onsubmit', 'return false');
                 });
                 $("#ajax :input, #ajax select").on('keyup change', function () {
                 Ajax(this);
                 });
                 $("form#ajaxclick a").on('click', function () {
                 Ajax(this);
                 });
                 $(".settingsajax").on("click", function () {
                 Ajax(this);
                 });
                 $('.close').on('click', function () {
                 Ajax("#ajax");
                 $(this).closest("#settings").hide(300);
                 $(this).closest("#settings").text("");
                 });
                 $('.btn.save').on('click', function () {
                 Ajax(this);
                 });
                 $('.folder-name').on('click', function () {
                 Ajax(this);
                 });
                 $('#pagenumber').on('keyup change', function () {
                 Ajax(this);
                 });
                 $('#first-page').on("click", function (e) {
                 firstPage(this);
                 });
                 $('#sidepanel-save').on("click", function (e) {
                 Ajax(this);
                 });
                 $('#last-page').on("click", function (e) {
                 lastPage(this);
                 });
                 $('#previous-page').on("click", function (e) {
                 prevPage(this);
                 });
                 $('#next-page').on("click", function (e) {
                 nextPage(this);
                 });
                 $(".settingsajax, #new").on("click", function () {
                 Ajax(this);
                 $('#settings').show(300);
                 });
                 $("#addnew").on("click", function () {
                 var item = $(this).siblings(".field-in.td").last();
                 var clone = item.last().clone();
                 clone.find(":text").val("");
                 item.after(clone);
                 console.log(clone);
                 });
                 
                 $('#fileupload').on("change", function () {
                 Ajax(this);
                 });*/
                $("form.fullajax").on('click', function () {
                    Ajax(this);
                });
                $("form.ajaxonchange").on('keyup change', function () {
                    Ajax(this);
                });

            });

            $(document).ajaxComplete(function () {
                $("form.ajaxonchange").on('keyup change', function () {
                    Ajax(this);
                });
                $("form.ajax .submit").on('click', function () {
                    var form = $(this).closest("form");
                    Ajax(form);
                });
                $("form.fullajax").on('click', function () {
                    Ajax(this);
                });
                $('.close-box .close').on("click", function () {
                    $(this).closest(".close-box").remove();
                });

                $('.message-box .message').mouseenter(function () {
                    clearTimeout(tId);
                    console.log("mose");
                });
                $('.message-box .message').mouseleave(function () {
                    console.log("mose1");
                    hideMessage();
                });
                /*$(function () {
                 $('form#ajax').attr('onsubmit', 'return false');
                 });*/
                /*$(function () {
                 if ($('#settings').text().length > 0) {
                 $('#settings').show(300);
                 }
                 ;
                 });*//*
                  $(function () {
                  $('form#pager').attr('onsubmit', 'return false');
                  });
                  $(function () {
                  $('.settingsajax').attr('onsubmit', 'return false');
                  });
                  $("#ajax :input, #ajax select").on('keyup change', function () {
                  Ajax(this);
                  });
                  $("form#ajaxclick a").on('click', function () {
                  Ajax(this);
                  });
                  $("#addnew").on("click", function () {
                  var item = $(this).siblings(".field-in.td").last();
                  var clone = item.last().clone();
                  clone.find(":text").val("");
                  item.after(clone);
                  console.log(item);
                  });
                  $(".settingsajax, #new").on("click", function () {
                  Ajax(this);
                  $('#settings').show(300);
                  });
                  $('.close').on('click', function () {
                  Ajax("#ajax");
                  $(this).closest("#settings").hide(300);
                  $(this).closest("#settings").text("");
                  });
                  $('.folder-name').on('click', function () {
                  Ajax(this);
                  });
                  $('.btn.save').on('click', function () {
                  Ajax(this);
                  });
                  $('#pagenumber').on('keyup change', function () {
                  Ajax(this);
                  });
                  
                  $('#sidepanel-save').on("click", function (e) {
                  Ajax(this);
                  });
                  $('#first-page').on("click", function (e) {
                  firstPage(this);
                  });
                  $('#last-page').on("click", function (e) {
                  lastPage(this);
                  });
                  $('#previous-page').on("click", function (e) {
                  prevPage(this);
                  });
                  $('#next-page').on("click", function (e) {
                  nextPage(this);
                  });*/
            });
        </script>

        <script>
            $(document).ready(function () {
                $('.expanded-btn').on("click", function () {
                    var element = $(this).closest(".expanded-directory").next(".expanded");
                    if (element.is(":hidden")) {
                        $(this).css({'transform': 'rotate(90deg)'});
                        element.show(500);
                    } else {
                        element.hide(500);
                        $(this).css({'transform': 'rotate(0deg)'});
                        element.find(".expanded").hide(500);
                        element.find(".expanded-btn").css({'transform': 'rotate(0deg)'});
                    }

                });
            });</script>
        <style>
            .filemanager{
                display:flex;
                margin-top:20px;
                height:500px;
                max-height:500px;
                overflow:hidden;
            }
            .filemanager .directory-tree-view{
                width:30%;
                padding-top:50px;
                min-width: 250px;
                border:2px solid black;
            }
            .filemanager .file-list{
                width:70%;
                min-width: 500px;
                border:2px solid black;
                overflow: auto;
            }
            .filemanager .file-list .table{
                margin:0;
                width: 100%;
                border-radius: 0;
            }
            .filemanager .file-list .table .tbody{
                max-height: 480px;
                height:480;
                overflow:auto;
            }
            ul.tree-view li{
                margin-left:10px;
                list-style-type: none;
                flex-direction: row;
                display: flex;
                align-items: center;
                flex-wrap: nowrap;
            }
            ul.tree-view li .folder-name, form.folder-name{
                cursor: pointer;
            }
            ul.tree-view li .folder-icon{
                margin-right:5px;
            }
            ul.tree-view li.alone .folder-icon{
                margin-left: 20px;
            }
            li.alone{
                margin-left:35px;
            }
            ul.expanded{
                margin-left:35px;
                display:none;
            }
            .expanded-btn{
                width:10px;
                cursor:pointer;
                margin-right: 10px;
            }
        </style>
    <body>

        <!-- nav class="navbar">
            <div class="navbar-logo">
                <img class="logo" src="https://www.infoartnet.hu/core/templates/ianet2017/gfx/logo_infoartnet.png" alt="InfoArtNet kft." title="InfoArtNet kft."/>
            </div>
            <div class="navbar-menu">
                <div class="navbar-menu-in">
                    <div class="box">
                        <i class="fas fa-bell"></i>

                    </div>
                    <div class="box">
                        <i class="far fa-user"></i>
                    </div>
                </div>
            </div>
        </nav>
        <div class="sidebar">
            <div class="sidebar-in">
                <div class="menu-block">
                    <div class="separator"><h3><?php echo T("system"); ?></h3><hr></div>
                    <ul>
        <?php //echo $awe->admMenu;     ?>
        <?php //getPos("systemmenu");       ?>
                    </ul>
                </div>
                <div class="menu-block">
                    <div class="separator"><h3><?php echo T("components"); ?></h3><hr></div>
                    <ul>
                        <li class="dropdown-btn"><a href="#">Dropdown</a></li>
                        <ul class="dropdown-menu">
                            <li><i class="fas fa-home"></i><a href="#">Legördülő</a></li>
                            <li><a href="#">D 1</a></li>
                            <li><a href="#">D 1</a></li>
                            <li class="dropdown-btn"><a href="#">Dropdown 1</a></li>
                            <ul class="dropdown-menu">
                                <li><i class="fas fa-home"></i><a href="#">Menüpont 0222222222222222222222222222221</a></li>
                                <li><a href="#">D 1</a></li>
                                <li><a href="#">D 1</a></li>
                                <li><a class="dropdown-btn" href="#">Dropdown 1</a></li>
                            </ul> 
                        </ul>  
                    </ul>
                </div>
            </div>

        </div-->

        <div class="message-box">
            <div class="message-box-in">
                <div class="message">
                    <div class="message-in">
                        <div class="message-head">
                            Hiba 404!
                        </div>
                        <div class="message-body">
                            Hiba .....;
                        </div>
                    </div>
                </div>
                <div class="message">
                    <div class="message-in">
                        <div class="message-head">
                            Hiba 404!
                        </div>
                        <div class="message-body">
                            Hiba .....;
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="progress-bar">
            <div class="bar" id="main-bar"></div>
        </div>
        <div class="content">
            <div class="content-in result">
                <h1><?php /* $GLOBALS['awe']->ComponentName; */ ?></h1>
                <?php getPos("content");
                ?>
            </div>
        </div>


        <!-- footer class="footer">
            <div class="footer-in">
                <a href="#" class="box">
                    <i class="fas fa-2x fa-question-circle"></i>
                </a>
                <a href="#" class="box">
                    <i class="fas fa-2x fa-book"></i>
                </a>
                <a href="#" class="box">
                    <i class="fas fa-2x fa-bug"></i>
                </a>
            </div>
        </footer-->
    </body>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>

    </script>
</html>
<style>
    .message-box{
        width:250px;
        position:fixed;
        bottom:10px;
        right:10px;
    }
    .message-box .message{
        background:var(--green);
        color:var(--black);
        padding: 20px 15px;
        margin:10px auto;
    }
    .progress-bar{
        position: fixed;
        top:0;
        height: 2px;
        width:100%;
    }
    .progress-bar .bar{
        width: 0%;
        height:2px;
        position: absolute;
        left:0;
        top:0;
        background-color: transparent;
        transition: .3s linear width;
    }
</style>