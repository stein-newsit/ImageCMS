{/*
/**
* @main.tpl - template for displaying shop main page
* Variables
*   $site_title: variable for insert site title
*   $canonical: variable for insert canonical
*   $site_description: variable for insert site description
*   $THEME: variable for template path
*   $site_keywords : variable for insert site keywords
*   $content : variable for insert content of page
*/}
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>{$site_title}</title>
        <meta name="description" content="{$site_description}" />
        <meta name="keywords" content="{$site_keywords}" />
        <meta name="generator" content="ImageCMS" />
        <meta name = "format-detection" content = "telephone=no" />
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0" />
        <link rel="stylesheet" type="text/css" href="{$THEME}css/style.css" media="all" />
        <link rel="stylesheet" type="text/css" href="{$THEME}{$colorScheme}/colorscheme.css" media="all" />
        <link rel="stylesheet" type="text/css" href="{$THEME}{$colorScheme}/color.css" media="all" />
        <link rel="stylesheet" type="text/css" href="{$THEME}css/adaptive.css" media="all" />

        {if $CI->uri->segment(1) == MY_Controller::getCurrentLocale()}
            {$lang = '/' . \MY_Controller::getCurrentLocale()} 
        {else:}
            {$lang = ''} 
        {/if}
        {if $CI->uri->segment(2) == 'profile' || $CI->uri->segment(1) == 'wishlist'}
            <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW" />
        {/if}
        <script type="text/javascript">
            var locale = "{echo $lang}";
        </script>
        <script type="text/javascript" src="{$THEME}js/jquery-1.8.3.min.js"></script>
        {include_tpl('config.js')}
        {literal}
            <script type="text/javascript">
                function initDownloadScripts(scripts, callback, customEvent) {
                    function downloadJSAtOnload(scripts, callback, customEvent) {
                        var cL = 0,
                                scriptsL = scripts.length;

                        $.map(scripts, function(i, n) {
                            $.ajax({
                                url: theme + 'js/' + i + '.js',
                                dataType: "script",
                                cache: true,
                                complete: function() {
                                    cL++;
                                    if (cL === scriptsL)
                                        if (callback) {
                                            eval(callback)();
                                            setTimeout(function() {
                                                $(document).trigger({'type': customEvent});
                                            }, 0);
                                        }
                                }
                            });
                        });
                    }
                    $(window).load(function(){
                        downloadJSAtOnload(scripts, callback, customEvent);
                    });
                }
            </script>
        {/literal}
        <!--[if lte IE 9]><script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <!--[if lte IE 8]><link rel="stylesheet" type="text/css" href="{$THEME}css/lte_ie_8.css" /><![endif]-->
        <!--[if IE 7]>
            <link rel="stylesheet" type="text/css" href="{$THEME}css/ie_7.css" />
            <script src="{$THEME}js/localStorageJSON.js"></script>
        <![endif]-->

        <link rel="icon" href="{echo siteinfo('siteinfo_favicon_url')}" type="image/x-icon" />
        <link rel="shortcut icon" href="{echo siteinfo('siteinfo_favicon_url')}" type="image/x-icon" />
    </head>
    <body class="is{echo $agent[0]} not-js {$CI->core->core_data['data_type']}{if $is_logged_in} isLogined{/if}">
        <script>
            if ('ontouchstart' in document.documentElement)
                document.body.className += ' isTouch';
            else
                document.body.className += ' notTouch';
        </script>
        {include_tpl('language/jsLangsDefine.tpl')}
        {include_tpl('language/jsLangs.tpl')}
        <div class="main-body">
            <div class="fon-header">
                <header>
                    {include_tpl('header')}
                </header>
                <div class="frame-menu-main horizontal-menu">
                    {\Category\RenderMenu::create()->setConfig(array('cache'=>TRUE))->load('category_menu')}
                </div>
                {if $CI->core->core_data['data_type'] == 'main'}
                    <div class="frame-baner frame-baner-start_page">
                        <section class="carousel-js-css baner container cycleFrame resize">
                            <!--remove class="resize" if not resize-->
                            <div class="p_r" style="height: 100%;">
                                <div class="content-carousel">
                                    <div class="f_l">
                                        {foreach $CI->load->module('banners')->getByGroup('leftStartPage') as $banner}
                                            <p>
                                                {if trim($banner.url)}
                                                    <a href="{site_url($banner.url)}"><img data-original="{echo $banner['photo']}" src="{$THEME}images/blank.gif" alt="{ShopCore::encode($banner.name)}"/></a>
                                                    {else:}
                                                    <span><img data-original="{echo $banner['photo']}" src="{$THEME}images/blank.gif" alt="{ShopCore::encode($banner.name)}"/></span>
                                                    {/if}
                                            </p>
                                        {/foreach}
                                    </div>
                                    <div class="f_r">
                                        {foreach $CI->load->module('banners')->getByGroup('rightStartPage') as $banner}
                                            <p>
                                                {if trim($banner.url)}
                                                    <a href="{site_url($banner.url)}"><img data-original="{echo $banner['photo']}" src="{$THEME}images/blank.gif" alt="{ShopCore::encode($banner.name)}"/></a>
                                                    {else:}
                                                    <span><img data-original="{echo $banner['photo']}" src="{$THEME}images/blank.gif" alt="{ShopCore::encode($banner.name)}"/></span>
                                                    {/if}
                                            </p>
                                        {/foreach}
                                    </div>
                                    {$CI->load->module('banners')->render()}
                                </div>
                            </div>
                            <div class="group-button-carousel">
                                <button type="button" class="prev arrow">
                                    <span class="icon_arrow_p"></span>
                                </button>
                                <button type="button" class="next arrow">
                                    <span class="icon_arrow_n"></span>
                                </button>
                            </div>
                        </section>
                    </div>
                {/if}
            </div>
            <div class="content">
                {$content}
            </div>
            <div class="h-footer{if $CI->core->core_data['data_type'] == 'main'} footer-main{/if}" data-mq-prop="height" data-mq-prop-pool="height" data-mq-elem-pool="footer"></div>
        </div>

        {include_tpl('footer')}

        {include_tpl('user_toolbar')}

        {/*}Start. delete before upload to server{ */}
        {/*}
        <!-- scripts -->
        <script type="text/javascript" src="{$THEME}js/raphael-min.js"></script>
        <script type="text/javascript" src="{$THEME}js/_united_side_plugins.js"></script>
        <script type="text/javascript" src="{$THEME}js/_plugins.js"></script>
        <script type="text/javascript" src="{$THEME}js/drop_extend_methods.js"></script>
        <script type="text/javascript" src="{$THEME}js/_shop.js"></script>
        <script type="text/javascript" src="{$THEME}js/_global_vars_objects.js"></script>
        <script type="text/javascript" src="{$THEME}js/_functions.js"></script>
        <script type="text/javascript" src="{$THEME}js/_scripts.js"></script>
        <!-- scripts end -->

        {literal}
            <script type="text/javascript">
                $(window).load(function() {
                    init();
                    setTimeout(function() {
                        $(document).trigger({type: 'scriptDefer'})
                    }, 0)
                })
            </script>
        {/literal}
        { */}
        {/*}End. delete before upload to server{ */}

        {/*fancybox}
        <link rel="stylesheet" type="text/css" href="{$THEME}js/fancybox/jquery.fancybox-1.3.4.css" media="all" />
        <script type="text/javascript" src="{$THEME}js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
        {end. fancybox*/}

        {/*}uncomment before opload to server and combine and minimize scripts (in comment <!-- scripts -->...<!-- scripts end -->) into united_scripts file{ */}
        {/*} Start. uncoment before development { */}

        <script type="text/javascript">
            initDownloadScripts(['raphael-min', 'united_scripts'], 'init', 'scriptDefer');
        </script>

        {/*} End. uncoment before development { */}
        {include_shop_tpl('js_templates')}
        <script type="text/javascript" src="{$THEME}js/adaptive.js"></script>
    </body>
</html>