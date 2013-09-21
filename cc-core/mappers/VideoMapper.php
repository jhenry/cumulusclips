<?php

class VideoMapper
{
    public function getVideoById($videoId)
    {
        $db = Registry::get('db');
        $query = 'SELECT * FROM ' . DB_PREFIX . 'videos WHERE video_id = :videoId';
        $dbResults = $db->fetchRow($query, array(':videoId' => $videoId));
        if ($db->rowCount() == 1) {
            return $this->_map($dbResults);
        } else {
            return false;
        }
    }
    
    public function getVideoByCustom(array $params)
    {
        $db = Registry::get('db');
        $query = 'SELECT * FROM ' . DB_PREFIX . 'videos WHERE ';
        
        $queryParams = array();
        foreach ($params as $fieldName => $value) {
            $query .= "$fieldName = :$fieldName AND ";
            $queryParams[":$fieldName"] = $value;
        }
        $query = rtrim($query, ' AND ');
        
        $dbResults = $db->fetchRow($query, $queryParams);
        if ($db->rowCount() == 1) {
            return $this->_map($dbResults);
        } else {
            return false;
        }
    }
    
    public function getVideoByFilename($filename)
    {
        $db = Registry::get('db');
        $query = 'SELECT * FROM ' . DB_PREFIX . 'videos WHERE filename = :filename';
        $dbResults = $db->fetchRow($query, array(':filename' => $filename));
        if ($db->rowCount() == 1) {
            return $this->_map($dbResults);
        } else {
            return false;
        }
    }
    
    public function getUserVideos($userId)
    {
        $db = Registry::get('db');
        $query = 'SELECT * FROM ' . DB_PREFIX . 'videos WHERE user_id = :userId';
        $dbResults = $db->fetchAll($query, array(':userId' => $userId));
        $userVideos = array();
        foreach($dbResults as $record) {
            $userVideos[] = $this->_map($record);
        }
        return $userVideos;
    }

    protected function _map($dbResults)
    {
        $video = new Video();
        $video->videoId = $dbResults['video_id'];
        $video->filename = $dbResults['filename'];
        $video->title = $dbResults['title'];
        $video->description = $dbResults['description'];
        $video->tags = explode(', ', $dbResults['tags']);
        $video->categoryId = $dbResults['category_id'];
        $video->userId = $dbResults['user_id'];
        $video->dateCreated = date(DATE_FORMAT, strtotime($dbResults['date_created']));
        $video->duration = $dbResults['duration'];
        $video->status = $dbResults['status'];
        $video->views = $dbResults['views'];
        $video->originalExtension = $dbResults['original_extension'];
        $video->featured = ($dbResults['featured'] == 1) ? true : false;
        $video->gated = ($dbResults['gated'] == 1) ? true : false;
        $video->released = ($dbResults['released'] == 1) ? true : false;
        $video->disableEmbed = ($dbResults['disable_embed'] == 1) ? true : false;
        $video->private = ($dbResults['private'] == 1) ? true : false;
        $video->privateUrl = $dbResults['private_url'];
        return $video;
    }

    public function save(Video $video)
    {
        $video = Plugin::triggerFilter('video.beforeSave', $video);
        $db = Registry::get('db');
        if (!empty($video->videoId)) {
            // Update
            Plugin::triggerEvent('video.update', $video);
            $query = 'UPDATE ' . DB_PREFIX . 'videos SET';
            $query .= ' filename = :filename, title = :title, description = :description, tags = :tags, category_id = :categoryId, user_id = :userId, date_created = :dateCreated, duration = :duration, status = :status, views = :views, original_extension = :originalExtension, featured = :featured, gated = :gated, released = :released, disable_embed = :disableEmbed, private = :private, private_url = :privateUrl';
            $query .= ' WHERE video_id = :videoId';
            $bindParams = array(
                ':videoId' => $video->videoId,
                ':filename' => $video->filename,
                ':title' => $video->title,
                ':description' => $video->description,
                ':tags' => implode(', ', $video->tags),
                ':categoryId' => $video->categoryId,
                ':userId' => $video->userId,
                ':dateCreated' => date(DATE_FORMAT, strtotime($video->dateCreated)),
                ':duration' => (!empty($video->duration)) ? $video->duration : null,
                ':status' => (!empty($video->status)) ? $video->status : 'new',
                ':views' => (!empty($video->views)) ? $video->views : 0,
                ':originalExtension' => (!empty($video->originalExtension)) ? $video->originalExtension : null,
                ':featured' => (isset($video->featured) && $video->featured === true) ? 1 : 0,
                ':gated' => (isset($video->gated) && $video->gated === true) ? 1 : 0,
                ':released' => (isset($video->released) && $video->released === true) ? 1 : 0,
                ':disableEmbed' => (isset($video->disableEmbed) && $video->disableEmbed === true) ? 1 : 0,
                ':private' => (isset($video->private) && $video->private === true) ? 1 : 0,
                ':privateUrl' => (!empty($video->privateUrl)) ? $video->privateUrl : null,
            );
        } else {
            // Create
            Plugin::triggerEvent('video.create', $video);
            $query = 'INSERT INTO ' . DB_PREFIX . 'videos';
            $query .= ' (filename, title, description, tags, category_id, user_id, date_created, duration, status, views, original_extension, featured, gated, released, disable_embed, private, private_url)';
            $query .= ' VALUES (:filename, :title, :description, :tags, :categoryId, :userId, :dateCreated, :duration, :status, :views, :originalExtension, :featured, :gated, :released, :disableEmbed, :private, :privateUrl)';
            $bindParams = array(
                ':filename' => $video->filename,
                ':title' => $video->title,
                ':description' => $video->description,
                ':tags' => implode(', ', $video->tags),
                ':categoryId' => $video->categoryId,
                ':userId' => $video->userId,
                ':dateCreated' => gmdate(DATE_FORMAT),
                ':duration' => (!empty($video->duration)) ? $video->duration : null,
                ':status' => (!empty($video->status)) ? $video->status : 'new',
                ':views' => (!empty($video->views)) ? $video->views : 0,
                ':originalExtension' => (!empty($video->originalExtension)) ? $video->originalExtension : null,
                ':featured' => (isset($video->featured) && $video->featured === true) ? 1 : 0,
                ':gated' => (isset($video->gated) && $video->gated === true) ? 1 : 0,
                ':released' => (isset($video->released) && $video->released === true) ? 1 : 0,
                ':disableEmbed' => (isset($video->disableEmbed) && $video->disableEmbed === true) ? 1 : 0,
                ':private' => (isset($video->private) && $video->private === true) ? 1 : 0,
                ':privateUrl' => (!empty($video->privateUrl)) ? $video->privateUrl : null,
            );
        }

        $db->query($query, $bindParams);
        $videoId = (!empty($video->videoId)) ? $video->videoId : $db->lastInsertId();
        Plugin::triggerEvent('video.save', $videoId);
        return $videoId;
    }
    
    public function getMultipleVideosById(array $videoIds)
    {
        $videoList = array();
        if (empty($videoIds)) return $videoList;
        
        $db = Registry::get('db');
        $inQuery = implode(',', array_fill(0, count($videoIds), '?'));
        $sql = 'SELECT * FROM ' . DB_PREFIX . 'videos WHERE video_id IN (' . $inQuery . ')';
        $result = $db->fetchAll($sql, $videoIds);

        foreach($result as $videoRecord) {
            $videoList[] = $this->_map($videoRecord);
        }
        return $videoList;
    }
}