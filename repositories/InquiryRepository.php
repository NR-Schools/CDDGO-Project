<?php

require_once "database/DatabaseConfig.php";
require_once "models/InquiryModel.php";

class InquiryRepository
{
    static function createInquiry(Inquiry $inquiry): bool
    {
        global $PDOConnection;

        return Database::SQLwithoutFetch(
            $PDOConnection,
            "
            INSERT INTO INQUIRIES
            VALUES (null, :studentId, null, :inquiryTitle, :inquiryDesc, FALSE, FALSE);
            ",
            [
                ":studentId" => $inquiry->Inquirer->StudID,
                ":inquiryTitle" => $inquiry->InquiryTitle,
                ":inquiryDesc" => $inquiry->InquiryDescription
            ]
        );
    }

    static function getAllInquiries(): array
    {
        global $PDOConnection;

        $queryResult = Database::SQLwithFetch(
            $PDOConnection,
            "
            SELECT * FROM INQUIRIES
                INNER JOIN STUDENTS
                    ON INQUIRIES.RepliedInquiry = STUDENTS.StudID
            ",
            []
        );

        $inquiryList = array();
        foreach ($queryResult as $inquiryRecord) {

            $student = new Student();
            $student->StudID = $inquiryRecord['StudID'];
            $student->StudNo = $inquiryRecord['StudNo'];
            $student->FirstName = $inquiryRecord['FirstName'];
            $student->LastName = $inquiryRecord['LastName'];
            $student->Program = $inquiryRecord['Program'];
            $student->Email = $inquiryRecord['Email'];
            $student->Password = $inquiryRecord['Password'];
            $student->isVerified = $inquiryRecord['isVerified'];

            $inquiry = new Inquiry();
            $inquiry->InquiryID = $inquiryRecord['InquiryID'];
            $inquiry->Inquirer = $student;
            $inquiry->RepliedInquiry = $inquiryRecord['RepliedInquiry'];
            $inquiry->InquiryTitle = $inquiryRecord['InquiryTitle'];
            $inquiry->InquiryDescription = $inquiryRecord['InquiryDescription'];
            $inquiry->isInquirySeen = $inquiryRecord['isInquirySeen'];
            $inquiry->isFromAdmin = $inquiryRecord['isFromAdmin'];

            $inquiryList[] = $inquiry;
        }

        return $inquiryList;
    }

    static function replyToInquiry(int $inquiryReplyingTo, Inquiry $newInquiry, bool $isAdminReplying)
    {
        global $PDOConnection;

        return Database::SQLwithoutFetch(
            $PDOConnection,
            "
        INSERT INTO INQUIRIES
        VALUES (null, :studentId, :inquiryReplyingTo, :inquiryTitle, :inquiryDesc, FALSE, :isFromAdmin)
        ",
            [
                ":studentId" => $newInquiry->Inquirer->StudID,
                ":inquiryReplyingTo" => $inquiryReplyingTo,
                ":inquiryTitle" => $newInquiry->InquiryTitle,
                ":inquiryDesc" => $newInquiry->InquiryDescription,
                ":isFromAdmin" => $isAdminReplying
            ]
        );
    }
}

?>