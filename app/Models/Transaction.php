<?php

declare(strict_types=1);

namespace App\Models;

use App\Model;
use DateTime;
use PDOException;

class Transaction extends Model
{

    private function store(): bool
    {
        $files = $this->getPathFiles();
        $data = $this->readCsvFiles($files);

        $return = $this->saveTransactions($data);

        return $return;
    }

    private function saveTransactions(array $data): bool
    {
        try {
            $values = '(' . rtrim(str_repeat('?,', count($data[0])), ',') . ')';
            $placeholders = rtrim(str_repeat($values . ',', count($data)), ',');

            $sql = "INSERT INTO transactions (`Date`, `Check`, Description, Amount) VALUES $placeholders";

            $stmt = $this->db->prepare($sql);

            $allValues = [];
            foreach ($data as $row) {
                $allValues = array_merge($allValues, $row);
            }

            $stmt->execute($allValues);
        } catch (PDOException $e) {
            return false;
        }

        return true;
    }

    public function getAllTransactions(): array|bool
    {
        try {
            $sth = $this->db->prepare("SELECT * FROM Transactions");
            $sth->execute();

            $result = $sth->fetchAll();

            return $result;
        } catch (PDOException $e) {
            return false;
        }
    }


    public function upload()
    {
        if (isset($_FILES["csv_files"])) {
            $file_count = count($_FILES["csv_files"]["name"]);

            for ($i = 0; $i < $file_count; $i++) {
                $file_name = $_FILES["csv_files"]["name"][$i];
                $tmp_name = $_FILES["csv_files"]["tmp_name"][$i];

                // Check if the uploaded file is a CSV file
                $file_info = pathinfo($file_name);
                if ($file_info["extension"] !== "csv") {
                    continue;
                }

                // Move file to the destination directory
                $target_file = STORAGE_PATH . "/" . $file_name;

                if (move_uploaded_file($tmp_name, $target_file)) {
                    if ($this->store()) {
                        header("Location: /?message=1");
                    } else {
                        header("Location: /upload?message=-1");
                    }
                    exit;

                    // Here you can add code to process the CSV file
                    // e.g., reading data from the file, data analysis, database insertion, etc.
                } else {

                    header("Location: /upload?message=-1");
                    exit;
                }
            }
        } else {
            header("Location: /upload?message=-2");
            exit;
        }

        header("Location: /upload?message=-3");
        exit;
    }

    private function getPathFiles(): array
    {
        // $files = scandir(STORAGE_PATH);
        $files = glob(STORAGE_PATH . '/*.csv');
        $filesList = [];

        foreach ($files as $file) {
            $filesList[] = $file;
        }

        return $filesList;
    }

    public function readCsvFiles(array $csvFilesPath): array
    {
        $data = [];

        foreach ($csvFilesPath as $csvFilePath) {
            if (file_exists($csvFilePath)) {
                if (($handle = fopen($csvFilePath, "r")) !== false) {
                    while (($row = fgetcsv($handle, 0, ",")) !== false) {
                        if ($row[0] === "Date") {
                            continue;
                        }

                        if ($this->changeFormatDate($row[0], "Y-m-d") !== false) {
                            $row[0] = $this->changeFormatDate($row[0], "Y-m-d");
                        }
                        if ($row[1] === '') {
                            $row[1] = null;
                        }

                        $data[] = $row;
                    }
                    fclose($handle);
                }
            }
        }

        return $data;
    }

    public function changeFormatDate(string $date, string $format = "M j, Y"): string|bool
    {
        if (strtotime($date) !== false) {
            $dateTime = new DateTime($date);

            // Sformatuj datę w wybranym formacie
            $formattedDate = $dateTime->format($format);

            return $formattedDate;
        }

        return false;
    }

    public function getAmountClassColumn(string $value): string
    {
        
        $value = str_replace(array("$", ","), "", $value);
        $value = (float) $value;

        if ($value > 0) {
            return "amount amount--green";
        } elseif ($value < 0) {
            return "amount amount--red";
        } else {
            return "amount";
        }
    }


    function getTotalPrices(array $data): array
    {
        $totalIncome = "0";
        $totalEpense = "0";
        $netTotal = "0";

        foreach ($data as $item) {
            $value = str_replace(array("$", ","), "", $item['Amount']);

            if ($value > 0) {
                $totalIncome = bcadd($totalIncome, $value, 2);
            } else {
                $totalEpense = bcadd($totalEpense, $value, 2);
            }

            $netTotal = bcadd($netTotal, $value, 2);
        }


        $totalPrices = array("totalIncome" => $totalIncome, "totalEpense" => $totalEpense, "netTotal" => $netTotal);

        foreach ($totalPrices as $key => $val) {
            $temp = $val;
            $val = number_format((float) $val, 2, ".", ",");

            if (bccomp($temp, "0", 2) === 0 || bccomp($temp, "0", 2) === 1) {
                $totalPrices[$key] = "$" . $val;
            } else {
                $totalPrices[$key] = str_replace("-", "-$", $val);
            }
        }

        return $totalPrices;
    }
}
