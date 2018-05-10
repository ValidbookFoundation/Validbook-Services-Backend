<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\helpers;

use app\modules\v1\models\story\Story;

class StoryHelper {

    public static function replacePlaceholdersToTags(
        $text,
        $forMeta = false,
        $forTwitter = false
    ) {

        $externalLink = '';

        if($forMeta === true) {
            $text = preg_replace('#<a.*?>.*?</a>#i', '', $text);
            $text = strip_tags($text);
        }

        if($forMeta && !$forTwitter)
            $text = htmlspecialchars($text);

        //If we have content card in SCard do not show URL that have generated it.
        if(!empty($externalLink) && strpos($text, htmlspecialchars($externalLink))  && !$forMeta)
            $text = preg_replace('#<a.*?>.*?</a>#i', '', $text);

        if (strstr($text, Story::STORY_VIDEO_PLACEHOLDER)) {
            $stringPlaceholder = preg_split(Story::STORY_VIDEO_PLACEHOLDER, $text);
            foreach ($stringPlaceholder as $key => $item) {
                if($key > 0) {
                    $pieces = explode("]", $stringPlaceholder[ $key ]);
                    $videoId = substr($pieces[0], 2);

                    $replacedTag = '';

                    $text = str_replace(
                        Story::STORY_VIDEO_PLACEHOLDER . '[' . $videoId . ']',
                        $replacedTag, $text
                    );
                }
            }
        }

        if (strstr($text, Story::STORY_IMAGE_PLACEHOLDER)) {

            $stringPlaceholder = preg_split(Story::STORY_IMAGE_PLACEHOLDER, $text);

            foreach ($stringPlaceholder as $key => $item) {
                if($key > 0) {
                    $pieces = explode("]", $stringPlaceholder[$key]);
                    $imageId = substr($pieces[0], 2);

                    $replacedTag = '';

                    $text = str_replace(
                        Story::STORY_IMAGE_PLACEHOLDER.'['.$imageId.']',
                        $replacedTag, $text
                    );
                }
            }
        }

        return $text;
    }
}