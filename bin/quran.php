#!/usr/bin/php
<?php

if(file_exists(__DIR__ . '/../vendor/autoload.php')) require_once(realpath(__DIR__ . '/../vendor/autoload.php'));
else require_once(realpath(__DIR__ . '/../../../autoload.php'));

use FaizShukri\Quran\Quran;

class QuranCommand
{
    public function execute(array $args)
    {
        $quran = new Quran(['storage_path' => __DIR__ . '/../quran']);

        try {
            if(isset($args[2])) $quran->translation($args[2]);
            $ayah = $quran->get($args[1]);

            return $this->parseResult($ayah) . "\n";

        } catch(Exception $e) {
            return "Error: " . $e->getMessage() . "\n\n";
        }
    }

    private function parseResult($args)
    {
        // Just a single ayah is return. No need to parse anything.
        if(is_string($args)) return $args . "\n";

        // Multiple ayah/one surah or multiple surah/one ayah. Not both.
        if(is_string(current($args))) return $this->buildAyah($args);

        // Both multiple ayah and multiple surah.
        $count = 0;
        $result = "\n";

        foreach($args as $translation => $aya) {

            $result .= strtoupper($translation) . "\n" . str_repeat('=', strlen($translation) + 2) . "\n\n";
            $result .= $this->buildAyah($aya);

            ++$count;
            if( $count < sizeof($args) ) $result .= "\n\n";
        }

        return $result;

    }

    private function buildAyah($ayah)
    {
        $result = "";
        foreach($ayah as $num => $aya) {
            $result .= "[ " . strtoupper($num) . " ]\t" . $aya . "\n";
        }
        return $result;
    }

    private function updateTranslation($translation)
    {

    }

}

$command = new QuranCommand;
echo $command->execute($argv);