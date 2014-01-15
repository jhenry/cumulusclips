<?php

class CategoryMapper extends MapperAbstract
{
    public function getCategoryById($categoryId)
    {
        return $this->getCategoryByCustom(array('category_id' => $categoryId));
    }
    
    public function getCategoryBySlug($slug)
    {
        return $this->getCategoryByCustom(array('slug' => $slug));
    }

    public function getCategoryByCustom(array $params)
    {
        $db = Registry::get('db');
        $query = 'SELECT * FROM ' . DB_PREFIX . 'categories WHERE ';
        
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

    protected function _map($dbResults)
    {
        $category = new Category();
        $category->categoryId = $dbResults['category_id'];
        $category->name = $dbResults['name'];
        $category->slug = $dbResults['slug'];
        return $category;
    }

    public function save(Category $category)
    {
        $category = Plugin::triggerFilter('video.beforeSave', $category);
        $db = Registry::get('db');
        if (!empty($category->categoryId)) {
            // Update
            Plugin::triggerEvent('video.update', $category);
            $query = 'UPDATE ' . DB_PREFIX . 'categories SET';
            $query .= ' name = :name, slug = :slug';
            $query .= ' WHERE category_id = :categoryId';
            $bindParams = array(
                ':categoryId' => $category->categoryId,
                ':name' => $category->name,
                ':slug' => $category->slug
            );
        } else {
            // Create
            Plugin::triggerEvent('video.create', $category);
            $query = 'INSERT INTO ' . DB_PREFIX . 'categories';
            $query .= ' (name, slug)';
            $query .= ' VALUES (:name, :slug)';
            $bindParams = array(
                ':name' => $category->name,
                ':slug' => $category->slug
            );
        }
            
        $db->query($query, $bindParams);
        $categoryId = (!empty($category->categoryId)) ? $category->categoryId : $db->lastInsertId();
        Plugin::triggerEvent('video.save', $categoryId);
        return $categoryId;
    }
    
    public function getCategoriesFromList(array $categoryIds)
    {
        $categoryList = array();
        if (empty($categoryIds)) return $categoryList;
        
        $db = Registry::get('db');
        $inQuery = implode(',', array_fill(0, count($categoryIds), '?'));
        $sql = 'SELECT * FROM ' . DB_PREFIX . 'categories WHERE category_id IN (' . $inQuery . ')';
        $result = $db->fetchAll($sql, $categoryIds);

        foreach($result as $categoryRecord) {
            $category = $this->_map($categoryRecord);
            $categoryList[$category->categoryId] = $category;
        }
        return $categoryList;
    }
    
    /**
     * Delete a category
     * @param integer $categoryId ID of category to be deleted
     * @return void Category is deleted from system
     */
    public function delete($categoryId)
    {
        $db = Database::GetInstance();
        Plugin::Trigger('category.delete');
        $query = 'DELETE FROM ' . DB_PREFIX . 'categories WHERE category_id = :categoryId';
        $db->query($query, array(':categoryId' => $categoryId));
    }
}