<article class="container">
    <label>
        <span class="frame_form_field__icsi-css">
            <div class="frameLabel__icsi-css error_text" name="error_text"></div>
        </span>
    </label>

{if count($wishlists)>0}
    {foreach $wishlists as $key => $wishlist}
        <form method="POST" action="/wishlist/updateWL">
            <table class="table">
                <input type="hidden" name="WLID" value="{echo $wishlist[0][wish_list_id]}">
                <thead>
                    <tr>
                        <td colspan="3">
                            <h1 class="wishListTitle">{$wishlist[0][title]}</h1>
                            <select name="access">
                                <option {if $wishlist[0][access] == 'shared'}selected="selected"{/if} value="shared">shared</option>
                                <option {if $wishlist[0][access] == 'private'}selected="selected"{/if} value="private">private</option>
                                <option {if $wishlist[0][access] == 'public'}selected="selected"{/if} value="public">public</option>
                            </select>
                            <div class="wishListDescription" >{$wishlist[0][description]}</div>
                            <a href="/wishlist/deleteWL/{$wishlist[0][wish_list_id]}">удалить</a>

                        </td>
                    </tr>
                    <tr>
                        <th>№</th>
                        <th>Отписатся</th>
                        <th>Товар</th>
                        <th>Коментарий</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach $wishlist as $key => $w}
                        <tr>
                            <td>{echo $key+1}</td>
                            <td>
                                <a href="/wishlist/deleteItem/{echo $w[variant_id]}/{echo $w[wish_list_id]}">удалить</a>
                            </td>
                            <td>
                                <a href="{shop_url('product/'.$w[url])}"
                                   title="{$w[name]}">
                                    {$w[name]}
                                </a>
                            </td>
                            <td>
                                <textarea name="comment[{echo $w[variant_id]}]">{$w[comment]}</textarea>
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
            {form_csrf()}
            <input type="submit" class="btn"/>
        </form>
    {/foreach}
{else:}
    Список Желания пуст
{/if}
</article>
