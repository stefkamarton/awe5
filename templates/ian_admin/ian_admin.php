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
            function Ajax(obj) {
                if (typeof (timeoutID) != "undefined" && timeoutID !== null) {
                    clearTimeout(timeoutID);
                }
                var url = $(obj).closest('form').data("url");
                var result = $(obj).closest('form').data("result");
                var method = $(obj).closest('form').data("method");
                var loadingmsg = $(obj).closest('form').data("loadingmsg");

                if (typeof (loadingmsg) != "undefined" && loadingmsg !== null) {
                    $(result).html(loadingmsg);
                }
                var waiting = $(obj).closest('form').data("waiting");
                if (typeof (waiting) == "undefined" && waiting == null) {
                    var waiting = 1500;
                }
                timeoutID = setTimeout(function () {
                    var formdata = $(obj).closest('form').serializeObject();
                    var data = {};
                    $.each(formdata, function (key, value) {
                        data[key] = value;
                    });

                    data['url_params'] = getUrlParameter('params');
                    if (typeof (data['url_params']) == "undefined" && data['url_params'] == null) {
                        data['url_params'] = "";
                    }

                    console.log(data['url_params']);
                    data['method'] = method;
                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: "json",
                        data: data,
                        beforeSend: function (data, textStatus) {
                            if (method == "save") {
                                $(result).html("Saving...");
                            } else {
                                $(result).html("<div class='message'><div class='info'><i class='fas fa-2x fa-info-circle'></i><div class='text'><?php echo T("loading") ?></div></div></div>");
                            }
                        },
                        success: function (data, textStatus) {
                            $(result).html(data.html);
                            if (typeof (data.url_params) != "undefined" && data.url_params !== null) {
                                window.history.replaceState({}, '<?php echo $GLOBALS['awe']->Domain; ?>', '<?php echo $GLOBALS['awe']->Url; ?>' + '?params=' + data.url_params);
                            }

                        },
                        error: function (req, status, err) {
                            console.log(req);
                            console.log('Something went wrong', status, err);
                        }
                    });
                }, waiting);
            }
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
                $(function () {
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
                $(".settingsajax").on("click", function () {
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

            });

            $(document).ajaxComplete(function () {
                $(function () {
                    $('form#ajax').attr('onsubmit', 'return false');
                });
                /*$(function () {
                 if ($('#settings').text().length > 0) {
                 $('#settings').show(300);
                 }
                 ;
                 });*/
                $(function () {
                    $('form#pager').attr('onsubmit', 'return false');
                });
                $(function () {
                    $('.settingsajax').attr('onsubmit', 'return false');
                });
                $("#ajax :input, #ajax select").on('keyup change', function () {
                    Ajax(this);
                });
                $("#addnew").on("click", function () {
                    var item = $(this).siblings(".field-in.td").last();
                    var clone = item.last().clone();
                    clone.find(":text").val("");
                    item.after(clone);
                    console.log(item);
                });
                $(".settingsajax").on("click", function () {
                    Ajax(this);
                    $('#settings').show(300);
                });
                $('.close').on('click', function () {
                    Ajax("#ajax");
                    $(this).closest("#settings").hide(300);
                    $(this).closest("#settings").text("");
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
                });
            });
        </script>


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
        <?php //echo $awe->admMenu;    ?>
        <?php //getPos("systemmenu");     ?>
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
    <script>/**
     * jQuery serializeObject
     * @copyright 2014, macek <paulmacek@gmail.com>
     * @link https://github.com/macek/jquery-serialize-object
     * @license BSD
     * @version 2.5.0
     */
         !function (e, i) {
             if ("function" == typeof define && define.amd)
                 define(["exports", "jquery"], function (e, r) {
                     return i(e, r)
                 });
             else if ("undefined" != typeof exports) {
                 var r = require("jquery");
                 i(exports, r)
             } else
                 i(e, e.jQuery || e.Zepto || e.ender || e.$)
         }(this, function (e, i) {
             function r(e, r) {
                 function n(e, i, r) {
                     return e[i] = r, e
                 }
                 function a(e, i) {
                     for (var r, a = e.match(t.key); void 0 !== (r = a.pop()); )
                         if (t.push.test(r)) {
                             var u = s(e.replace(/\[\]$/, ""));
                             i = n([], u, i)
                         } else
                             t.fixed.test(r) ? i = n([], r, i) : t.named.test(r) && (i = n({}, r, i));
                     return i
                 }
                 function s(e) {
                     return void 0 === h[e] && (h[e] = 0), h[e]++
                 }
                 function u(e) {
                     switch (i('[name="' + e.name + '"]', r).attr("type")) {
                         case"checkbox":
                             return"on" === e.value ? !0 : e.value;
                         default:
                             return e.value
                     }
                 }
                 function f(i) {
                     if (!t.validate.test(i.name))
                         return this;
                     var r = a(i.name, u(i));
                     return l = e.extend(!0, l, r), this
                 }
                 function d(i) {
                     if (!e.isArray(i))
                         throw new Error("formSerializer.addPairs expects an Array");
                     for (var r = 0, t = i.length; t > r; r++)
                         this.addPair(i[r]);
                     return this
                 }
                 function o() {
                     return l
                 }
                 function c() {
                     return JSON.stringify(o())
                 }
                 var l = {}, h = {};
                 this.addPair = f, this.addPairs = d, this.serialize = o, this.serializeJSON = c
             }
             var t = {validate: /^[a-z_][a-z0-9_]*(?:\[(?:\d*|[a-z0-9_]+)\])*$/i, key: /[a-z0-9_]+|(?=\[\])/gi, push: /^$/, fixed: /^\d+$/, named: /^[a-z0-9_]+$/i};
             return r.patterns = t, r.serializeObject = function () {
                 return new r(i, this).addPairs(this.serializeArray()).serialize()
             }, r.serializeJSON = function () {
                 return new r(i, this).addPairs(this.serializeArray()).serializeJSON()
             }, "undefined" != typeof i.fn && (i.fn.serializeObject = r.serializeObject, i.fn.serializeJSON = r.serializeJSON), e.FormSerializer = r, r
         });</script>
</html>