<?php

namespace App\Service;

class Parser
{
    public function parse(array $contentData, string $contentKey, string $titleKey): array
    {
        $parsedData = [];
        foreach ($contentData as $datum) {
            $title = preg_replace('/<a\s+.*?>(.*?)<\/a>/', '$1', $datum[$titleKey]);
            $tmpArr['title'] = preg_replace('/<img\s+.*?>/', '', $title);
            $tmpArr['content'] = preg_replace('/<a\s+.*?>(.*?)<\/a>/', '$1', $datum[$contentKey]);
            $tmpArr['content'] = preg_replace('/<img\s+.*?>/', '', $tmpArr['content']);
            $parsedData[] = $tmpArr;
        }

        return $parsedData;
    }
}