<div class="container">
    <section class="mini-layout">
        <div class="frame_title clearfix">
            <div class="pull-left">
                <span class="help-inline"></span>
                <span class="title">{lang('a_create_n_user_m')}</span>
            </div>
            <div class="pull-right">
                <div class="d-i_b">
                    <a href="/admin/components/cp/user_manager" class="t-d_n m-r_15 pjax"><span class="f-s_14">←</span> <span class="t-d_u">{lang('a_return')}</span></a>                   
                    <button type="button" class="btn btn-small btn-success action_on formSubmit" data-form="#create" data-action="close" data-submit><i class="icon-plus-sign icon-white"></i>{lang('a_create')}</button>
                    <button type="button" class="btn btn-small action_on formSubmit" data-form="#create" data-action="exit"><i class="icon-check"></i>{lang('a_cre_exit_form')}</button>
                </div>
            </div>                            
        </div>


        <!----------------------------------------------------- CREATE USER-------------------------------------------------------------->
        <div class="tab-pane">
            <table class="table table-striped table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th colspan="6">
                            {lang('a_data_n_user')}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6">
                            <div class="inside_padd span12">
                                <div class="form-horizontal">
                                    <div class="row-fluid">
                                        <form id="create" method="post" active="{$SELF_URL}/create_user/">
											
											<div class="control-group">
                                            	<label class="control-label" for="email">{lang('amt_email')}</label>
                                                <div class="controls">
                                                    <input type="text" name="email" id="email" value="" class="required email" autocomplete="off"/>
                                                </div>
                                            </div>

                                            <div class="control-group">
                                                <label class="control-label" for="username">{lang('a_fio')}:</label>
                                                <div class="controls">
                                                    <input type="text" name="username" id="username" value="" required autocomplete="off"/>
                                                </div>
                                            </div>

                                            <div class="control-group">
                                                <label class="control-label" for="phone">{lang('a_phone')}</label>
                                                <div class="controls">
                                                    <input type="text" name="phone" id="phone" value="" autocomplete="off"/>
                                                </div>
                                            </div>

                                            <div class="control-group">
                                                <label class="control-label" for="password">{lang('amt_new_pass')}</label>
                                                <div class="controls">
                                                    <input type="password" name="password" id="password" value="" required autocomplete="off"/>
                                                </div>
                                            </div>



                                            <div class="control-group">
                                                <label class="control-label" for="password_conf">{lang('amt_new_pass_confirm')}</label>
                                                <div class="controls">
                                                    <input type="password" name="password_conf" id="password_conf" value="" required/>
                                                </div>
                                            </div>

                                            
                                            <div class="control-group">
                                                <label class="control-label" for="role">{lang('amt_group')}</label>
                                                <div class="controls">
                                                    <select name="role" id="role">
                                                        {foreach $roles as $role}
                                                            <option value ="{$role.id}">{$role.alt_name}</option>
                                                        {/foreach}
                                                    </select>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table> 
        </div>
    </section>
</div>