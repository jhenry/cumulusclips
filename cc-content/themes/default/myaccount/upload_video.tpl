<?php
$this->addJs('fileupload.jquery-ui.widget.js');
//$this->addJs('fileupload.iframe-transport');
$this->addJs('fileupload.plugin.js');
$this->addJs('fileupload.js');
$this->setLayout('myaccount');
?>

<h1><?=Language::getText('upload_video_header')?></h1>

<div class="message"></div>

<p><?=Language::getText('upload_video_text')?></p>
<p class="big"><?=Language::getText('filesize_limit')?>: <?=round($config->video_size_limit/1048576)?>MB</p>
<p class="big"><?=Language::getText('upload_supported_formats') . ': ' . implode(', ', $config->accepted_video_formats)?></p>

<form name="upload" action="<?=HOST?>/myaccount/upload/validate/">
    <div id="upload-select-file" class="button">
        <span><?=Language::getText('browse_files_button')?></span>
        <input id="upload" type="file" name="upload" />
    </div>
    <input id="upload_button" class="button" type="button" value="<?=Language::getText('upload_video_button')?>" />
    <input type="hidden" name="upload-limit" id="upload-limit" value="<?=$config->video_size_limit?>" />
    <input type="hidden" name="file-types" id="file-types" value="<?=htmlspecialchars(json_encode($config->accepted_video_formats))?>" />
    <input type="hidden" name="upload-type" id="upload-type" value="video" />
    
    <div id="upload_status">
        <div class="title"></div>
        <div class="progress">
            <a href="" title="<?=Language::getText('cancel')?>"><?=Language::getText('cancel')?></a>
            <div class="meter">
                <div class="fill"></div>
            </div>
            <div class="percentage">0%</div>
        </div>
    </div>
    
</form>