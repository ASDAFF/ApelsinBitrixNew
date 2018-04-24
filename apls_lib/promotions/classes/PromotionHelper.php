<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/mysql/MySQLTrait.php";

class PromotionHelper
{
    use MySQLTrait;

    const CONNECTION_NAME = "APLS_PROMOTION";

    const GLOBAL_ACTIVITY = "global_activity";
    const LOCAL_ACTIVITY = "local_activity";
    const VK_ACTIVITY = "vk_activity";
    const DEFAULT_ACTIVITY = self::GLOBAL_ACTIVITY;

    private static $activityTypes = array(
        self::GLOBAL_ACTIVITY,
        self::LOCAL_ACTIVITY,
        self::VK_ACTIVITY
    );

    public static function getActualPromotionsDataForRegion($region, $activityType = self::DEFAULT_ACTIVITY) {
        if(!in_array($activityType, static::$activityTypes)) {
            $activityType = self::DEFAULT_ACTIVITY;
        }
        $sql = "SELECT
                    REV.`promotion`, 
                    RIS.`revision`, 
                    RIS.`section`,
                    REV.`global_activity`,
                    REV.`local_activity`,
                    REV.`vk_activity`
                    FROM (
                        SELECT
                        REV.`id`, 
                        REV.`promotion`,
                        REV.`global_activity`,
                        REV.`local_activity`,
                        REV.`vk_activity`
                        FROM (
                            SELECT 
                            `id`, 
                            `promotion`,
                            `global_activity`,
                            `local_activity`,
                            `vk_activity`
                            FROM `apls_promotions_revision` 
                            WHERE 
                            `disable`<'1' AND
                            `apply_from` < now()
                            order by `apply_from` DESC
                        ) as REV
                        group by REV.`promotion`
                    ) as REV
                    RIGHT JOIN `apls_promotions_in_sections` as RIS
                    on REV.`id` = RIS.`revision`
                    WHERE 
                    `$activityType` > '0' AND
                    REV.`promotion` IS NOT NULL AND 
                    (
                        RIS.`revision` IN (SELECT `revision` FROM `apls_promotions_in_region` WHERE `region`='$region') OR
                        RIS.`revision` NOT IN (SELECT `revision` FROM `apls_promotions_in_region` WHERE `region`<>'$region')
                    )";
        $records = static::getConnection(self::CONNECTION_NAME)->query($sql);
        $promotions = array();
        $revisions = array();
        $sections = array();
        $promotionsInSections = array();
        while ($record = $records->fetch()) {
            $promotionsInSections[$record['section']][] = $record['promotion'];
            $promotions[] = $record['promotion'];
            $revisions[] = $record['revision'];
            $sections[] = $record['section'];
        }
        $result = array();
        $result['promotions'] = array_unique($promotions);
        $result['revisions'] = array_unique($revisions);
        $result['sections'] = array_unique($sections);
        $result['promotionsInSections'] = $promotionsInSections;
        return $result;
    }
}