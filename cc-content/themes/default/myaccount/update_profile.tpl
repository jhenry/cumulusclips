<?php
$this->addJs('fileupload.jquery-ui.widget.js');
//$this->addJs('fileupload.iframe-transport');
$this->addJs('fileupload.plugin.js');
$this->addJs('fileupload.js');
$this->SetLayout('myaccount');
?>

<h1><?=Language::GetText('profile_header')?></h1>

<div class="message <?=$message_type?>"><?=$message?></div>

<div class="form wide">
    <p class="large"><?=Language::GetText('personal_header')?></p>
    <p><?=Language::GetText('asterisk')?></p>
    <form action="<?=HOST?>/myaccount/profile/" method="post">
        <label class="<?=(isset ($Errors['first_name'])) ? ' error' : ''?>"><?=Language::GetText('first_name')?>:</label>
        <input class="text" type="text" name="first_name" value="<?=htmlspecialchars($loggedInUser->firstName)?>" />

        <label class="row<?=(isset ($Errors['last_name'])) ? ' error' : ''?>"><?=Language::GetText('last_name')?>:</label>
        <input class="text" type="text" name="last_name" value="<?=htmlspecialchars($loggedInUser->lastName)?>" />

        <label class="row<?=(isset ($Errors['email'])) ? ' error' : ''?>">*<?=Language::GetText('email')?>:</label>
        <input class="text" type="text" name="email" value="<?=htmlspecialchars($loggedInUser->email)?>" />

        <label class="row<?=(isset ($Errors['website'])) ? ' error' : ''?>"><?=Language::GetText('website')?>:</label>
        <input class="text" type="text" name="website" value="<?=htmlspecialchars($loggedInUser->website)?>" />

        <label class="row<?=(isset ($Errors['about_me'])) ? ' error' : ''?>"><?=Language::GetText('about_me')?>:</label>
        <textarea class="text" name="about_me" rows="10" cols="45"><?=htmlspecialchars($loggedInUser->aboutMe)?></textarea>

        <input type="hidden" value="yes" name="submitted" />
        <input class="button" type="submit" name="button" value="<?=Language::GetText('profile_button')?>" />
    </form>
</div>

<h1><?=Language::GetText('update_avatar_header')?></h1>
<div id="update_avatar" class="form">

    <div class="left">
        <?php $avatar = $this->getService('User')->getAvatarUrl($loggedInUser); ?>
        <p class="avatar"><span><img alt="<?=Language::GetText('current_avatar')?>" src="<?=($avatar) ? $avatar : THEME . '/images/avatar.gif'?>"></span></p>
        <?=Language::GetText('current_avatar')?><br />
        <a class="confirm" data-node="confirm_reset_avatar" href="<?=HOST?>/myaccount/profile/reset/"><?=Language::GetText('reset_avatar')?></a>
    </div>

    <div class="right">
        <?=Language::GetText('update_avatar_text')?>
        <form name="upload" action="<?=HOST?>/myaccount/upload/avatar/">
            <div id="upload-select-file" class="button">
                <span><?=Language::getText('browse_files_button')?></span>
                <input id="upload" type="file" name="upload" />
            </div>
            <input id="upload_button" class="button" type="button" value="<?=Language::GetText('update_avatar_button')?>" />
            <input type="hidden" name="timestamp" value="<?=$timestamp?>" />
            <input type="hidden" name="upload-limit" id="upload-limit" value="<?=1024*30?>" />
            <input type="hidden" name="file-types" id="file-types" value="<?=htmlspecialchars(json_encode($config->accepted_avatar_formats))?>" />
            <input type="hidden" name="upload-type" id="upload-type" value="avatar" />

            <div id="upload_status">
                <div class="title"></div>
                <div class="progress">
                    <a href="" title="<?=Language::GetText('cancel')?>"><?=Language::GetText('cancel')?></a>
                    <div class="meter">
                        <div class="fill"></div>
                    </div>
                    <div class="percentage">0%</div>
                </div>
            </div>

        </form>
    </div>
</div>