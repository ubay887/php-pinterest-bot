<?php

namespace seregazhuk\PinterestBot\Helpers\Providers;

class PinHelper extends RequestHelper
{
    /**
     * Create Pinterest API request form commenting pin
     *
     * @param int    $pinId
     * @param string $text
     * @return array
     */
    public static function createCommentRequest($pinId, $text)
    {
        $dataJson = self::createPinRequest($pinId, 'pin_id');
        $dataJson["options"]["text"] = $text;

        return self::createPinRequestData($dataJson);
    }

    /**
     * Create Pinterest API request form commenting pin
     *
     * @param int $pinId
     * @param int $commentId
     * @return array
     */
    public static function createCommentDeleteRequest($pinId, $commentId)
    {
        $dataJson = self::createPinRequest($pinId, 'pin_id');
        $dataJson["options"]["comment_id"] = $commentId;

        return self::createPinRequestData($dataJson);
    }

    /**
     * Creates Pinterest API request for Pin creation
     *
     * @param string $description
     * @param string $imageUrl
     * @param int    $boardId
     * @return array
     */
    public static function createPinCreationRequest($imageUrl, $boardId, $description = "")
    {
        $dataJson = [
            "options" => [
                "method"      => "scraped",
                "description" => $description,
                "link"        => $imageUrl,
                "image_url"   => $imageUrl,
                "board_id"    => $boardId,
            ],
        ];

        return self::createPinRequestData($dataJson, "/pin/create/bookmarklet/?url=" . urlencode($imageUrl));
    }


    /**
     * Creates Pinterest API request for Pin repin
     *
     * @param string $description
     * @param int    $repinId
     * @param int    $boardId
     * @return array
     */
    public static function createRepinRequest($repinId, $boardId, $description)
    {
        $dataJson = [
            "options" => [
                "board_id"    => $boardId,
                "description" => stripslashes($description),
                "link"        => stripslashes($repinId),
                "is_video"    => null,
                "pin_id"      => $repinId,
            ],
            "context" => [],
        ];

        return self::createPinRequestData($dataJson);
    }


    /**
     * Creates Pinterest API request to get Pin info
     *
     * @param int $pinId
     * @return array
     */
    public static function createInfoRequest($pinId)
    {
        $dataJson = [
            "options" => [
                "field_set_key"                  => "detailed",
                "fetch_visualsearchCall_objects" => true,
                "id"                             => $pinId,
                "pin_id"                         => $pinId,
                "allow_stale"                    => true,
            ],
        ];

        return self::createPinRequestData($dataJson);
    }

    /**
     * Creates common pin request data by PinId
     *
     * @param int    $pinId
     * @param string $template
     * @return array
     */
    public static function createPinRequest($pinId, $template = 'id')
    {
        return [
            "options" => [
                "$template" => $pinId,
            ],
            "context" => [],
        ];
    }

    /**
     * Creates simple Pin request by PinId (used by delete and like requests)
     *
     * @param $pinId
     * @return array
     */
    public static function createSimplePinRequest($pinId)
    {
        $dataJson = self::createPinRequest($pinId);
        return self::createPinRequestData($dataJson);
    }

    /**
     * @param string|null $sourceUrl
     * @param array       $data
     * @return array
     */
    public static function createPinRequestData($data, $sourceUrl = null)
    {
        if ($sourceUrl === null) {
            reset($data);
            $sourceUrl = "/pin/" . end($data["options"]) . "/";
        }

        return self::createRequestData($data, $sourceUrl);
    }
}
