<?php

namespace App\Imports;

use App\Country;
use App\Models\Branches;
use App\Models\ClientsModel;
use App\Models\DocumentTypes;
use App\State;
use Carbon\Carbon; 
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; 
use PhpOffice\PhpSpreadsheet\Shared\Date;
class ClientsImport implements ToModel, WithHeadingRow
{
    private $verified = false;
    private $rows = 0;
    private $rowdata = [];
    private $header = [];
    private $errors = [];
    private $emails = [];
    private $suites = [];
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function model(array $row)
    {

        $this->rowdata[] = $row;
        ++$this->rows;
        $keyNo = $this->rows - 1;
        $suite = $row['suite'];
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $email = $row['email'];
        $password = $row['password'];
        $phone_number = $row['phone_number'];
        $address = $row['address'];
        $country_name = $row['country_name'];
        $state_name = $row['state_name'];
        $branch_name = $row['branch_name'];
        $document_type = $row['document_type'];
        $document_number = $row['document_number'];
        $postal_code = $row['postal_code'];
        $email_verified_date = $row['email_verified_date'];
        $locality = $row['locality'];
        $person_or_company = $row['person_or_company'];
        $company_name = $row['company_name'];

        if ($this->rows == 1) {
            $this->header = array_keys($row);
        }
        $flag = true;
        $error = [];
        $isCountryExists = null;
        $isStateExists = null;
        $isEmailAlreadyExists = ClientsModel::whereRaw('LOWER(email) = ?', [strtolower($email)])->exists();
        $isBranchExists = Branches::whereRaw('LOWER(branch) = ?', [strtolower($branch_name)])->exists();
        $isDocumentTypeExist = DocumentTypes::whereRaw('LOWER(document_name) = ?', [strtolower($document_type)])->exists();
        if(!empty($suite)){
            $isSuiteExist = ClientsModel::whereRaw('LOWER(suite) = ?', [strtolower($suite)])
            ->exists();
            
            if($isSuiteExist){
                if (isset($this->rowdata[$keyNo])) {
                 
                    unset($this->rowdata[$keyNo]);
                }
                // $flag = false;
                // $error['suite'] = 'Suite already assosiated with another client.';
            }
        }
        if (empty($first_name)) {
            $flag = false;
            $error['first_name'] = 'First Name is empty';
        }
        if (empty($last_name)) {
            $flag = false;
            $error['last_name'] = 'Last Name is empty';
        }
        if (empty($email)) {
            $flag = false;
            $error['email'] = 'Email is empty';
        } else if ($isEmailAlreadyExists) {
            if (isset($this->rowdata[$keyNo])) { 
                unset($this->rowdata[$keyNo]);
            }
            
        }
        if (empty($password)) {
            $flag = false;
            $error['password'] = 'Password is empty';
        }
        if (empty($address)) {
            $flag = false;
            $error['address'] = 'Address is empty';
        }
        // if (empty($country_name)) {
        //     $flag = false;
        //     $error['country_name'] = 'Country Name is empty';
        // } else if (!$isCountryExists) {
        //     $flag = false;
        //     $error['country_name'] = 'Country does not exist in system records.';
        // }
        if(!empty($country_name)){
            $isCountryExists = Country::whereRaw('LOWER(name) = ?', [strtolower($country_name)])->first();
            if(!$isCountryExists){
                $flag = false;
                $error['country_name'] = 'Country does not exist in system records.';
            }
            if(!empty($state_name)){
                $isStateExists = State::where('country_id', $isCountryExists->id)
                ->whereRaw('LOWER(name) = ?', [strtolower($state_name)])
                ->exists();
                if(!$isStateExists){
                    $flag = false;
                    $error['state_name'] = 'State Name does not exist in system records.';
                }
            }
        }
        // if (empty($state_name)) {
        //     $flag = false;
        //     $error['state_name'] = 'State Name is empty';
        // } else if (!$isStateExists) {
        //     $flag = false;
        //     $error['state_name'] = 'State does not exist in system records.';
        // }
        if (empty($branch_name)) {
            $flag = false;
            $error['branch_name'] = 'Branch Name is empty';
        } else if (!$isBranchExists) {
            $flag = false;
            $error['branch_name'] = 'Branch does not exist in system records.';
        }
        if (empty($document_type)) {
            $flag = false;
            $error['document_type'] = 'Document Type  is empty';
        } else if (!$isDocumentTypeExist) {
            $flag = false;
            $error['document_type'] = 'Document Type does not exist in system records.';
        }
        if (empty($document_number)) {
            $flag = false;
            $error['document_number'] = 'Document Number  is empty';
        }
        if ($flag) {
            $combinedEmail = mb_strtolower($email);
            if (!in_array($combinedEmail, $this->emails)) {
                $this->emails[] = $combinedEmail;
            }else{
                if (isset($this->rowdata[$keyNo])) {
                     unset($this->rowdata[$keyNo]);
                }
            }
            //  else {
            //     $flag = false;
            //     $error['email'] = 'Email duplicate in sheet.';
            // }
            if(!empty($suite)){
                $combinedSuite = mb_strtolower($suite);
                if (!in_array($combinedSuite, $this->suites)) {
                    $this->suites[] = $combinedSuite;
                }else {
                    if (isset($this->rowdata[$keyNo])) {
                        // Remove the index from the array
                        unset($this->rowdata[$keyNo]);
                    } 
                }
                //  else {
                //     $flag = false;
                //     $error['suite'] = 'Suite duplicate in sheet.';
                // }
            }
            
            if(!empty($email_verified_date)){
                $dateExcel      = (float)$row['email_verified_date'];
                $dateObject     = Date::excelToDateTimeObject($dateExcel);
                $date           = Carbon::instance($dateObject)->toDateString();
                $isValidFormat  = preg_match('/^\d{4}-\d{2}-\d{2}$/', $date);
                if ($date) {
                    $isValidFormat = preg_match('/^\d{4}-\d{2}-\d{2}$/', $date);
                    if ($isValidFormat) {
                    } else {
                        $error['email_verified_date'] = "Invalid Date formate.";
                        $flag = false;
                    }
                } else {
                    $error['email_verified_date'] = "Invalid Date formate.";
                    $flag = false;
                }
            }
            if (!empty($person_or_company)) {
                $normalizedValue = strtolower($person_or_company);
                if ($normalizedValue != 'person' && $normalizedValue != 'company') {
                    $error['person_or_company'] = "Invalid Person or Company.";
                    $flag = false;
                }
            }
            
            
        }



        if ($flag == false) {
            $this->errors[] = [
                'row_no' => $this->rows + 1,
                'row_data' => $row,
                'row_errors' => $error,
            ];
        } 
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }
    public function getHeader(): array
    {
        return $this->header;
    }
    public function getData(): array
    {
        return $this->rowdata;
    }
    public function getErrors(): array
    {
        return $this->errors;
    }
}
