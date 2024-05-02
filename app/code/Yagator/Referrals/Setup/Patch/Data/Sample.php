<?php
declare(strict_types=1);

namespace Yagator\Referrals\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\File\Csv;


class Sample implements DataPatchInterface, PatchRevertableInterface
{

    const CSV_FILE = 'sample_referrals.csv';

    /**
     * @var ModuleDataSetupInterface
     */
    protected $moduleDataSetup;
    /**
     * @var Csv
     */
    protected $csv;
    /**
     * @var File
     */
    protected $filesystem;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        Csv $csv,
        File $filesystem
    ){
        $this->moduleDataSetup = $moduleDataSetup;
        $this->csv = $csv;
        $this->filesystem = $filesystem;
    }

    /**
     * @return string[]
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @return string[]
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @return $this
     */
    public function apply()
    {
        $connection = $this->moduleDataSetup->getConnection();
        $connection->startSetup();

        $sample_data = $this->getSampleData();
        if ($sample_data !== false){
            $table = $connection->getTableName('referral');
            $fields = [];
            foreach ($sample_data as $data) {
                if (count($fields) == 0){
                    $fields = $data;
                } else {
                    $insert_data = [];
                    foreach ($fields as $key=>$field) {
                        $insert_data[$field] = $data[$key];
                    }
                    $connection->insert($table, $insert_data);
                }
            }
        } else {
            echo 'CSV file does not exists or is empty';
        }

        $connection->endSetup();
    }

    /**
     * @return void
     */
    public function revert()
    {
        $connection = $this->moduleDataSetup->getConnection();
        $connection->startSetup();

        $table = $connection->getTableName('referral');
        $connection->delete($table);

        $connection->endSetup();
    }

    private function getSampleData()
    {
        $file = __DIR__ . '/' . self::CSV_FILE;
        if ($this->filesystem->isExists($file)) {
            $this->csv->setDelimiter(',');
            return $this->csv->getData($file);
        }

        return false;
    }
}
