<div class="wrap">
    <h1>Personizely</h1>
    <?php if ($data['api_key']) : ?>
    <div class="notice notice-success">
        <p>The plugin is connected to your Personizely account. You're all set.</p>
    </div>
    <div>
        <a href="<?=$data['app_url'];?>/widgets?create=1" target="_blank" rel="noopener" class="button button-primary">Create a Widget</a>
        <a href="<?=$data['app_url'];?>/campaigns?create=1" target="_blank" rel="noopener" class="button button-primary">Create a Personalization Campaign</a>
        <a href="<?=$data['app_url'];?>/" target="_blank" rel="noopener" class="button button-primary">Open Dashboard</a>
        <a href="#" class="button" onclick="toggleApiKeyForm();">Toggle Advanced Settings</a>
    </div>
    <form method="POST" novalidate="novalidate" id="apiKeyForm" style="display: none;">
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="api_key">API Key</label>
                </th>
                <td>
                    <input type="text" name="api_key" class="regular-text" value="<?=$data['api_key']?>"/>
                    <p class="description">You can find the API Key in the <a href="<?=$data['app_url'];?>/settings" target="_blank" rel="noopener">Personizely Settings</a>.</p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="async">Async</label>
                </th>
                <td>
                    <input name="async" type="checkbox" value="1" <?=$data['async'] ? 'checked': ''?>/>
                    <p class="description">Async loading may make the site faster but can make your widget and campaigns show up after a delay.</p>
                </td>
            </tr>
            </tbody>
        </table>
        <p class="submit">
            <input type="hidden" name="nonce" value="<?=$data['nonce']?>">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
        </p>
    </form>
    <?php else : ?>
    <div>
        <a href="<?=$data['app_url'].$data['register_url'];?>" class="button button-primary">Create an Account and Connect</a>
        <a href="<?=$data['app_url'].$data['connect_url'];?>" class="button">Connect an Existing Account</a>
    </div>
    <?php endif ?>
</div>
<script>
    function toggleApiKeyForm () {
        var $form = document.querySelector('#apiKeyForm')
        $form.style.display = $form.style.display === 'none' ? 'block' : 'none'
    }
</script>
