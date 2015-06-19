<?php
Zend_Loader::loadClass('CommonPost');
class ContactUs extends CommonPost
{
    protected function getPostId()
    {
        $sQuery = "
                    SELECT b_posts.id
                    FROM
                    b_posts_post_types 
                            INNER JOIN b_posts 
                            ON b_posts_post_types.post_id = b_posts.id
                            INNER JOIN b_post_types
                            ON b_posts_post_types.post_type_id = b_post_types.id
                    WHERE
                            b_post_types.name = '".SiteConstants::$CONTACT."'
                            AND b_posts.deleted = 0
                             ";

       return $this->oDb->fetchOne($sQuery);        
    }
}