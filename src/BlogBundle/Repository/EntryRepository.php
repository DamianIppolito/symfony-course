<?php
/**
 * Created by PhpStorm.
 * User: Damian
 * Date: 13/10/2017
 * Time: 9:34
 */
namespace BlogBundle\Repository;
use BlogBundle\Entity\Tag;
use BlogBundle\Entity\EntryTag;

class EntryRepository extends \Doctrine\ORM\EntityRepository{

    /**
     * @param null $tags
     * @param null $title
     * @param null $category
     * @param null $user
     * @param null $entry
     */
    public function saveEntryTags($tags=null, $title=null, $category=null, $user=null, $entry=null){

        $em = $this->getEntityManager();
        $tags_repo = $em->getRepository("BlogBundle:Tag");
        if($entry == null){
            $entry = $this->findOneBy(array(
                "title" => $title,
                "category" => $category,
                "user" => $user
            ));
        }else{}

        $tags = explode(",",$tags);
        foreach ($tags as $tag){
            $tag = trim($tag);
            $isset_tag = $tags_repo->findOneBy(array("name" => $tag));
            if(count($isset_tag) == 0){
                $tag_obj = new Tag();
                $tag_obj->setName($tag);
                $tag_obj->setDescription($tag);
                if(!empty($tag)) {
                    $em->persist($tag_obj);
                    $flush = $em->flush();
                }
            }
            $tag = $tags_repo->findOneBy(array("name" => $tag));
            $entryTag = new EntryTag();
            $entryTag->setEntry($entry);
            $entryTag->setTag($tag);
            $em->persist($entryTag);
        }
        $em->flush();
        return $flush;
    }

}