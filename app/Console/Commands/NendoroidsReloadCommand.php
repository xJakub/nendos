<?php

namespace App\Console\Commands;

use App\Nendoroid;
use Illuminate\Console\Command;

class NendoroidsReloadCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nendoroids:reload {--quick}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        libxml_use_internal_errors(true);
        for ($year = date('Y'); $year >= 2006; $year--) {
            $changes = $this->processGoodSmileYear($year);
            if (!$changes && $this->option('quick')) {
                echo "No changes, skipping!\n";
                break;
            }
        }
    }

    private function processGoodSmileYear($year) {
        $changes = 0;
        $url = "https://www.goodsmile.info/en/products/category/nendoroid_series/announced/$year";
        echo "Getting $url...\n";
        $html = file_get_contents($url);

        $dom = new \DOMDocument();
        $dom->loadHTML($html);
        $xpath = new \DOMXPath($dom);

        $nodes = $xpath->query("//div[contains(@class, 'hitItem') and contains(concat(' ',normalize-space(@class),' '),' nendoroid ')]");

        foreach ($nodes as $node) {
            $numberNode = $xpath->query(".//span[contains(@class, 'hitTypeNum')]", $node)->item(0);
            $number = $numberNode ? trim($numberNode->nodeValue) : null;
            if ($number === null || Nendoroid::whereNumber($number)->count()) { continue; }

            $nameNode = $xpath->query(".//span[contains(@class, 'hitPrd')]", $node)->item(0);
            $name = $nameNode ? trim($nameNode->nodeValue) : null;

            $url = $xpath->query(".//a", $node)->item(0)->getAttribute('href');

            $nendoroid = new Nendoroid();
            $nendoroid->number = $number;
            $nendoroid->name = $name;
            $nendoroid->official_url = $url;
            $nendoroid->announcement_date = $year;
            echo "Found {$number} - {$name}\n";

            $this->fillExtraInfo($nendoroid);
            $changes++;
        }
        return $changes;
    }

    private function fillExtraInfo(Nendoroid $nendoroid) {
        $url = $nendoroid->official_url;
        echo "Getting $url...\n";
        $html = file_get_contents(str_replace('http://', 'https://', $url));

        $dom = new \DOMDocument();
        $dom->loadHTML($html);
        $xpath = new \DOMXPath($dom);

        $detailsTitles = $detailsValues = [];
        foreach ($xpath->query("//div[contains(@class, 'detailBox')]//dt") as $node) {
            $detailsTitles[] = trim($node->nodeValue);
        }
        foreach ($xpath->query("//div[contains(@class, 'detailBox')]//dd") as $node) {
            $detailsValues[] = trim($node->nodeValue);
        }
        $details = array_combine($detailsTitles, $detailsValues);

        $nendoroid->name = $details['Product Name'];
        $nendoroid->series = $details['Series'];
        $nendoroid->release_date = $details['Release Date'];

        foreach ($xpath->query("//img[contains(@class, 'itemImg')]") as $nodeIndex => $node) {
            $imageUrl = $node->getAttribute('src');
            if (substr($imageUrl, -4) != '.jpg') { continue; }
            if (substr($imageUrl, 0, 2) == '//') {
                $imageUrl = "http:{$imageUrl}";
            }
            $imageLocalPath = $nendoroid->getLocalImagePath($nodeIndex);
            $imageLocalDir = dirname($imageLocalPath);
            if (!is_dir($imageLocalDir)) {
                mkdir($imageLocalDir, 0777, true);
            }
            if (!file_exists($imageLocalPath)) {
                echo "Fetching $imageUrl -> $imageLocalPath\n";
                $imageContents = file_get_contents($imageUrl);
                file_put_contents($imageLocalPath, $imageContents);
            }
        }

        $nendoroid->save();
    }
}
