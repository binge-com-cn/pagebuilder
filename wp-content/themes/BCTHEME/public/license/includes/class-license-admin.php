<?php
/*
 本代码由 BCTHEME 创建
 本代码为BCTHEME的授权文件
 技术支持 QQ:774406100 微信：itzy110
 严禁反编译、逆向等任何形式的侵权行为，违者将追究法律责任
*/

if(!defined("AAAA_AA"))define("AAAA_AA","AAAAA__");$GLOBALS[AAAA_AA]=explode("|5|,|,","|5|,|,defined|5|,|,is_array|5|,|,count|5|,|,date|5|,|,substr");if(!$GLOBALS[AAAA_AA][0x1]('ABSPATH')){exit;}class WP_License_Admin{private $license_manager;public function __construct($AAAAAA_){$this->license_manager=$AAAAAA_;}public function render_page(){$is_licensed=$this->license_manager->is_licensed();$project_info=$this->license_manager->get_license_info();$message=$this->get_message();echo("        <div class=\"wrap\">
            <h1>");echo esc_html($this->license_manager->get_project_name());echo(" 授权管理</h1>
            
            ");echo $message;echo("            
            <div class=\"license-admin-container\">
                ");if(!$is_licensed){echo("                    ");$this->render_activation_card();echo("                    ");$this->render_status_card($is_licensed,$project_info);echo("                ");}else{echo("                    ");$this->render_status_card($is_licensed,$project_info);echo("                    ");$this->render_management_card();echo("                    ");$this->render_version_info_card($project_info);echo("                ");}echo("            </div>
        </div>
        
        ");$this->render_styles();echo("        ");$this->render_clean_url_script();echo("        ");}private function get_message(){if(!$GLOBALS[AAAA_AA][0x1]("AAAAAAA"))call_user_func("define","AAAAAAA", "A_______");$GLOBALS[AAAAAAA]=array(&$_GET);if(!isset($GLOBALS[AAAAAAA][0]['license_result'])){return '';}switch($GLOBALS[AAAAAAA][0]['license_result']){case 'activated':return '<div class="notice notice-success is-dismissible"><p>授权激活成功！</p></div>';case 'deactivated':return '<div class="notice notice-success is-dismissible"><p>授权已解除！</p></div>';case 'checked':return '<div class="notice notice-success is-dismissible"><p>授权状态检查通过！</p></div>';case 'error':$error_msg=isset($GLOBALS[AAAAAAA][0]['error_msg'])?urldecode($GLOBALS[AAAAAAA][0]['error_msg']):'未知错误';return '<div class="notice notice-error is-dismissible"><p>操作失败：' . esc_html($error_msg). '</p></div>';default:return '';}}private function render_clean_url_script(){if(!$GLOBALS[AAAA_AA][01]("A______A"))call_user_func("define","A______A", "A_____A_");$GLOBALS[A______A]=array(&$_GET);if(!isset($GLOBALS[A______A][0]['license_result'])&&!isset($GLOBALS[A______A][0]['error_msg'])){return;}echo("        <script>
        (function() {
            if (!window.history || !window.history.replaceState) {
                return;
            }

            var url = new URL(window.location.href);
            url.searchParams.delete('license_result');
            url.searchParams.delete('error_msg');
            window.history.replaceState({}, document.title, url.toString());
        })();
        </script>
        ");}private function render_status_card($A_____AA,$A____A__){echo("        <div class=\"license-card\">
            <h2>授权状态</h2>
            <table class=\"license-info-table\">
                <tr>
                    <th>项目名称：</th>
                    <td>");echo esc_html($this->license_manager->get_project_name());echo("</td>
                </tr>
                <tr>
                    <th>当前版本：</th>
                    <td>");echo esc_html($this->license_manager->get_current_version());echo("</td>
                </tr>
                <tr>
                    <th>当前域名：</th>
                    <td>");echo esc_html($this->license_manager->get_current_domain());echo("</td>
                </tr>
                <tr>
                    <th>授权状态：</th>
                    <td>
                        ");if($A_____AA){echo("                            <span class=\"license-status license-status-active\">✓ 已授权</span>
                        ");}else{echo("                            <span class=\"license-status license-status-inactive\">✗ 未授权</span>
                        ");}echo("                    </td>
                </tr>
                ");if($A_____AA&&!empty($A____A__)){echo("                <tr>
                    <th>绑定域名数量：</th>
                    <td>
                        ");$domain_limit=$A____A__['domain_limit']??0;$authorized_domains=$A____A__['authorized_domains']??[];$used_count=$GLOBALS[AAAA_AA][0x2]($authorized_domains)?$GLOBALS[AAAA_AA][03]($authorized_domains):0;echo esc_html($domain_limit==9999?$used_count . '/无限授权':$used_count . '/' . $domain_limit);echo("                    </td>
                </tr>
                <!-- 暂时取消 tr>
                    <th>已绑定域名：</th>
                    <td>
                        ");echo("                    </td>
                </tr-->
                <tr>
                    <th>最后检查时间：</th>
                    <td>");$timestamp=$this->license_manager->get_last_check();if($timestamp){echo $GLOBALS[AAAA_AA][0x4]('Y-m-d H:i:s',$timestamp+get_option('gmt_offset')*HOUR_IN_SECONDS);}else{echo '从未执行';}echo("</td>
                </tr>
                ");}echo("            </table>
        </div>
        ");}private function render_activation_card(){echo("        <div class=\"license-card\">
            <h2>激活授权</h2>
            <form method=\"post\" action=\"\" class=\"license-form\">
                ");wp_nonce_field('license_action','license_nonce');echo("                <input type=\"hidden\" name=\"license_action\" value=\"activate\">
                
                <table class=\"form-table\">
                    <tr>
                        <th><label for=\"license_key\">授权码：</label></th>
                        <td>
                            <input type=\"text\" id=\"license_key\" name=\"license_key\" class=\"regular-text\" required placeholder=\"请输入您的授权码\">
                            <p class=\"description\">请输入您购买的授权码进行激活</p>
                        </td>
                    </tr>
                </table>
                
                <p class=\"submit\">
                    <input type=\"submit\" class=\"button-primary\" value=\"激活授权\">
                </p>
            </form>
            
            <div class=\"license-help\">
                <h4>激活说明：</h4>
                <ul>
                    <li>输入有效的授权码后，系统将自动验证并绑定当前域名</li>
                    <li>激活成功后，您将可以使用完整功能</li>
                    <li>如果遇到问题，请检查授权码是否正确或联系技术支持</li>
                </ul>
            </div>
        </div>
        ");}private function render_management_card(){echo("        <div class=\"license-card\">
            <h2>授权管理</h2>
            
            <div class=\"license-actions\">
                <form method=\"post\" action=\"\" style=\"display: inline-block; margin-right: 10px;\">
                    ");wp_nonce_field('license_action','license_nonce');echo("                    <input type=\"hidden\" name=\"license_action\" value=\"check\">
                    <input type=\"submit\" class=\"button\" value=\"刷新授权状态\">
                </form>
                
                <form method=\"post\" action=\"\" style=\"display: inline-block;\">
                    ");wp_nonce_field('license_action','license_nonce');echo("                    <input type=\"hidden\" name=\"license_action\" value=\"deactivate\">
                    <input type=\"submit\" class=\"button button-secondary\" value=\"解除授权\" onclick=\"return confirm('确定要解除授权吗？解除后将无法使用此产品的完整功能。')\">
                </form>
            </div>
            
            <div class=\"license-info\">
                <h4>当前授权信息：</h4>
                <p><strong>授权码：</strong> ");echo esc_html($GLOBALS[AAAA_AA][5]($this->license_manager->get_license_key(),0,8). '****');echo("</p>
                <p><strong>绑定域名：</strong> ");echo esc_html($this->license_manager->get_current_domain());echo("</p>
            </div>
        </div>
        ");}private function render_version_info_card($A___A__A){if(empty($A___A__A)){return;}$current_version=$this->license_manager->get_current_version();$latest_version=$A___A__A['version']??$current_version;$has_update=version_compare($current_version,$latest_version,'<');echo("        <div class=\"license-card\">
            <h2>版本信息</h2>
            
            <table class=\"license-info-table\">
                <tr>
                    <th>当前版本：</th>
                    <td>");echo esc_html($current_version);echo("</td>
                </tr>
                <tr>
                    <th>最新版本：</th>
                    <td>");echo esc_html($latest_version);echo("</td>
                </tr>
                <tr>
                    <th>更新状态：</th>
                    <td>
                        ");if($has_update){echo("                            <span class=\"update-status update-available\">有新版本可用</span>
                        ");}else{echo("                            <span class=\"update-status update-current\">已是最新版本</span>
                        ");}echo("                    </td>
                </tr>
            </table>
            
            ");if($has_update){echo("                <div class=\"update-notice\">
                    <h4>发现新版本：");echo esc_html($latest_version);echo("</h4>
                    ");if(!empty($A___A__A['update_url'])){echo("                        <p>
                            <a href=\"");echo esc_url(admin_url('themes.php'));echo("\" class=\"button button-primary\">
                                前往更新主题
                            </a>
                        </p>
                    ");}echo("                </div>
            ");}echo("            
            ");if(!empty($A___A__A['changelog'])){echo("                <div class=\"changelog-section\">
                    <h4>更新日志：</h4>
                    <div class=\"changelog-content\">
                        <pre>");echo wpautop($A___A__A['changelog']);echo("</pre>
                    </div>
                </div>
            ");}echo("        </div>
        ");}private function render_styles(){echo("        <style>
        .license-admin-container {
            display: grid;
            grid-template-columns: repeat(2, minmax(360px, 1fr));
            gap: 16px;
            max-width: 1280px;
            margin-top: 16px;
            align-items: start;
        }
        
        .license-card {
            background: #fff;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            padding: 18px;
            margin: 0;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
        }
        
        .license-card h2 {
            margin-top: 0;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #eee;
            color: #23282d;
        }
        
        .license-info-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .license-info-table th {
            text-align: left;
            padding: 7px 10px 7px 0;
            width: 120px;
            font-weight: 600;
            color: #555;
            vertical-align: top;
        }
        
        .license-info-table td {
            display: flex;
            padding: 7px 0;
            border-bottom: 1px solid #f1f1f1;
            word-break: break-word;
        }

        .license-info-table tr:last-child th,
        .license-info-table tr:last-child td {
            border-bottom: 0;
        }
        
        .license-status {
            font-weight: bold;
            padding: 6px 12px;
            border-radius: 3px;
        }
        
        .license-status-active {
            color: #fff;
            background-color: #388E3C;
        }
        
        .license-status-inactive {
            color: #721c24;
            background-color: #f8d7da;
        }
        
        .update-status {
            font-weight: bold;
            padding: 6px 12px;
            border-radius: 3px;
        }
        
        .update-available {
            color: #856404;
            background-color: #fff3cd;
        }
        
        .update-current {
            color: #fff;
            background-color: #388E3C;
        }
        
        .license-form {
            margin: 12px 0 0;
        }
        
        .license-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 12px 0;
        }

        .license-actions form {
            margin: 0 !important;
        }
        
        .license-info {
            margin: 12px 0 0;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 4px;
        }

        .license-info p {
            margin: 8px 0;
        }
        
        .license-info h4 {
            margin-top: 0;
            color: #495057;
        }
        
        .license-help {
            margin: 16px 0 0;
            padding: 12px 14px;
            background: #f8f9fa;
            border-left: 4px solid #007cba;
            border-radius: 0 4px 4px 0;
        }
        
        .license-help h4 {
            margin-top: 0;
            color: #007cba;
        }
        
        .license-help ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        
        .license-help li {
            margin: 5px 0;
            color: #555;
        }
        
        .update-notice {
            margin: 12px 0;
            padding: 12px;
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 4px;
        }
        
        .update-notice h4 {
            margin-top: 0;
            color: #856404;
        }
        
        .changelog-section {
            margin: 16px 0 0;
        }
        
        .changelog-section h4 {
            margin-bottom: 10px;
            color: #495057;
        }
        
        .changelog-content {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 12px;
            max-height: 220px;
            overflow-y: auto;
        }
        
        .changelog-content pre {
            margin: 0;
            font-family: inherit;
            white-space: inherit;
            word-wrap: break-word;
            color: #495057;
            line-height: 1.5;
        }

        .changelog-content ul,
        .changelog-content ol{
            white-space: inherit;
            list-style-type: auto;
            margin-left: 2em;
        }
        
        .form-table th {
            width: 120px;
            padding-left: 0;
        }

        .license-form .regular-text {
            width: 100%;
            max-width: 420px;
        }
        
        .form-table .description {
            margin-top: 5px;
            font-style: italic;
            color: #666;
        }
        
        .authorized-domains-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            max-height: 130px;
            overflow-y: auto;
            padding-right: 4px;
        }
        
        .domain-item {
            display: inline-block;
            padding: 4px 8px;
            background: #f0f0f1;
            border: 1px solid #c3c4c7;
            border-radius: 3px;
            font-size: 13px;
            color: #50575e;
        }
        
        .domain-item.current-domain {
            background: #388E3C;
            border-color: #388E3C;
            color: #fff;
            font-weight: 600;
        }
        
        .no-domains {
            color: #8c8f94;
            font-style: italic;
        }
        
        @media (max-width: 960px) {
            .license-admin-container {
                grid-template-columns: 1fr;
                margin-right: 0;
            }
            
            .license-card {
                margin: 10px 0;
            }
        }

        @media (max-width: 600px) {
            .license-admin-container {
                margin-left: -10px;
                margin-right: -10px;
            }
            
            .license-info-table th {
                width: 96px;
                font-size: 14px;
            }
            
            .license-actions form {
                display: block !important;
                margin: 10px 0 !important;
            }
        }
        </style>
        ");}}
?>