<?php

require_once "database/DatabaseConfig.php";
require_once "models/MemberModel.php";

class MemberRepository
{
    static function createMember(int $studentId, Member $member): bool
    {
        return Database::SQLwithoutFetch(
            Database::getPDO(),
            "
            INSERT INTO MEMBERS VALUES (:studentId, :position, :yearJoined);
            ",
            [
                ":studentId" => $studentId,
                ":position" => $member->Position,
                ":yearJoined" => $member->YearJoined
            ]
        );
    }

    static function getAllMembers(): array
    {
        $queryResult = Database::SQLwithFetch(
            Database::getPDO(),
            "
            SELECT * FROM MEMBERS
                INNER JOIN STUDENTS
                    ON MEMBERS.MemberID = STUDENTS.StudID;
            ",
            []
        );

        $memberList = array();
        foreach ($queryResult as $memberRecord) {

            $student = new Student();
            $student->StudID = $memberRecord['StudID'];
            $student->StudNo = $memberRecord['StudNo'];
            $student->FirstName = $memberRecord['FirstName'];
            $student->LastName = $memberRecord['LastName'];
            $student->Program = $memberRecord['Program'];
            $student->Email = $memberRecord['Email'];
            $student->Password = $memberRecord['Password'];
            $student->isVerified = $memberRecord['isVerified'];

            $member = new Member();
            $member->student = $student;
            $member->Position = $memberRecord['Position'];
            $member->YearJoined = $memberRecord['YearJoined'];

            $memberList[] = $member;
        }

        return $memberList;
    }

    static function updateMember(Member $member): bool
    {
        return Database::SQLwithoutFetch(
            Database::getPDO(),
            "
            UPDATE MEMBERS
            SET
                Position = :position
                AND YearJoined = :yearJoined
            WHERE MemberID = :studentId;
            ",
            [
                ":studentId" => $member->student->StudID,
                ":position" => $member->Position,
                ":yearJoined" => $member->YearJoined
            ]
        );
    }
}

?>