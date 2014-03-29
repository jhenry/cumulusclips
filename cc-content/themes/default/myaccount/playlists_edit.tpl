<?php $this->SetLayout ('myaccount'); ?>
<?php $playlistService = $this->getService('Playlist'); ?>

<h1><?=Language::GetText('playlists_edit_header')?>: <?=$playlistService->getPlaylistName($playlist)?></h1>
<p><a href="<?=HOST?>/myaccount/playlists/"><?=Language::GetText('back_to_playlists')?></a></p>
        
<?php if ($message): ?>
    <div class="message <?=$message_type?>"><?=$message?></div>
<?php endif; ?>
 
    
<?php if ($playlist->type == 'playlist'): ?>
    <!-- BEGIN UPDATE PLAYLIST FORM -->
    <h2><?=Language::GetText('update_playlist_header')?></h2>
    <div class="form playlists_form">
        <form method="POST">
            <div class="field">
                <label><?=Language::GetText('playlist_name')?>:</label>
                <input class="text" type="text" name="name" value="<?=$playlist->name?>" />
            </div>
            <div class="field">
                <label><?=Language::GetText('visibility')?>:</label>
                <select name="visibility">
                    <option value="public" <?=($playlist->public) ? 'selected="selected"' : ''?>><?=Language::GetText('public')?></option>
                    <option value="private" <?=(!$playlist->public) ? 'selected="selected"' : ''?>><?=Language::GetText('private')?></option>
                </select>
            </div>
            <input type="hidden" name="submitted" value="true" />
            <input class="button" type="submit" value="<?=Language::GetText('update_playlist_button')?>" />
        </form>
    </div>
    <!-- END UPDATE PLAYLIST FORM -->
<?php endif; ?>
    

<h2><?=Language::GetText('playlist_videos')?></h2>
<?php if (count($playlist->entries) > 0): ?>

    <div class="videos_list">
    <?php foreach ($videoList as $video): ?>
        <?php $videoService = $this->getService('Video'); ?>
        <div class="video">
            <div>
                <a href="<?=$videoService->getUrl($video)?>/?playlist=<?=$playlist->playlistId?>" title="<?=$video->title?>">
                    <img width="165" height="92" src="<?=$config->thumb_url?>/<?=$video->filename?>.jpg" />
                </a>
                <span><?=$video->duration?></span>
            </div>
            <p><a href="<?=$videoService->getUrl($video)?>/?playlist=<?=$playlist->playlistId?>" title="<?=$video->title?>"><?=$video->title?></a></p>
            <p class="actions small">
                <a class="confirm" data-node="confirm_remove_playlist_video" href="<?=HOST?>/myaccount/playlists/edit/<?=$playlist->playlistId?>/?remove=<?=$video->videoId?>" title="<?=Language::GetText('remove_playlist_video')?>"><span><?=Language::GetText('remove_playlist_video')?></span></a>
            </p>
        </div>
    <?php endforeach; ?>
    </div>

<?php else: ?>
    <p><strong><?=Language::GetText('no_videos_playlist')?></strong></p>
<?php endif; ?>